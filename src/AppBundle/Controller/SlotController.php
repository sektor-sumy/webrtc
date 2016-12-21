<?php
/**
 * Created by PhpStorm.
 * User: Patrick
 * Date: 14.12.2016
 * Time: 16:04
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Meeting;
use AppBundle\Entity\Slot;
use AppBundle\Entity\User;
use AppBundle\Form\Type\SlotCreateType;
use AppBundle\Form\Type\SlotEditType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Security("has_role('ROLE_USER')")
 */
class SlotController extends Controller
{
    /**
     * @Route("/users/{id}/slots", name="get_user_slots")
     * @Security("has_role('ROLE_STUDENT')")
     * @ParamConverter("user", options={"mapping": {"id": "id"}})
     * @Method("GET")
     */
    public function getUserSlotsAction(User $user)
    {
        if (!$user || !$user->hasRole(User::ROLE_STUDENT) || ($user != $this->getUser() && !$this->getUser()->hasRole(User::ROLE_ADMIN))) throw $this->createNotFoundException();

        return $this->createApiResponse($user->getSlots());
    }

    /**
     * @Route("/meetings/{id}/slots", name="post_meeting_slots")
     * @Security("has_role('ROLE_STUDENT')")
     * @ParamConverter("meeting", options={"mapping": {"id": "id"}})
     * @Method("POST")
     */
    public function postMeetingSlotsAction(Request $request, Meeting $meeting)
    {
        if(!$meeting || $meeting->getStatus() !== Slot::STATUS_OPEN) throw $this->createNotFoundException();

        $slot = new Slot();
        $form = $this->createForm(SlotCreateType::class, $slot);
        $form->handleRequest($request);

        if($form->isValid())
        {
            $slot->setStudent($this->getUser());
            $slot->setMeeting($meeting);
            $slot->setStatus(Slot::STATUS_OPEN);

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($slot);
            $em->flush();
        }else{
            return $this->createApiResponse(['form_error' => $form->getErrors(true)]);
        }
        return $this->createApiResponse(['valid' => true, 'status' => 'Slot created']);
    }



    /**
     * @Route("/meetings/{meetingid}/slots/{slotid}", name="patch_meeting_slot")
     * @Security("has_role('ROLE_PROF')")
     * @ParamConverter("meeting", options={"mapping": {"id": "meetingid"}})
     * @ParamConverter("slot", options={"mapping": {"id": "slotid"}})
     * @Method("PATCH")
     */
    public function patchMeetingSlotAction(Request $request, Meeting $meeting, Slot $slot)
    {
        if(!$slot || !$meeting || $slot->getMeeting() != $meeting || ($meeting->getProfessor() != $this->getUser() && !$this->getUser()->hasRole(User::ROLE_ADMIN))) throw $this->createNotFoundException();

        $form = $this->createForm(SlotEditType::class, $slot);
        $form->handleRequest($request);

        if($form->isValid())
        {
            /** @var EntityManagerInterface $em */
            $em = $this->get('doctrine.orm.entity_manager');

            $startDate = $meeting->getStartDate();

            // set start and endpoint
            /** @var Slot $lastAcceptedSlotDuration */
            $lastAcceptedSlotDuration = $em->getRepository('AppBundle:Slot')
                ->findLastAcceptedSlotByMeeting($meeting);

            if($lastAcceptedSlotDuration)
            {
                $startDate = $lastAcceptedSlotDuration->getDate()->add(new \DateInterval('PT' . $lastAcceptedSlotDuration->getDuration() . 'M'));
            }

            $slot->setDate($startDate);
            $em->persist($slot);
            $em->flush();
        }else{
            return $this->createApiResponse(['form_error' => $form->getErrors(true)]);
        }
        return $this->createApiResponse(['valid' => true, 'status' => 'Slot updated', 'entity' => $slot->getId()]);
    }
}