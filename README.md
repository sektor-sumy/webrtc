# WinfProjekt2-WebRTC

This project is part of a project work at the university of applied sciences Stuttgart and is not meant to be used for commercial purposes.

## Installation

This project currently runs on Linux - Ubuntu 14.04.

If you want to install the project locally, then:

- You will need a webserver (e.g. Apache) that supports php for the REST API
- You will need a websocket server (e.g. SocketIO - here we used ratchet implementation) for webrtc signaling mechanism
- You will need a database (e.g. MySQL) that is compatibel with Doctrine ORM
- You might need a proxy in order to run both of the above described servers, here we used reverse proxy HAProxy

- Finally just clone the project, and run standard symfony commands to install the project (composer, doctrine)

## Usage

- Go to: https://chor-am-killesberg.de:8000
- You can use one of the following users: 
  - role: PROFESSOR, username: hoess, password: Test1234
  - role: STUDENT, username: ottooffline, password: Test1234

## Contributing

Patrick, Markus, Sina, Tanja, Jennifer, Hadi

## Code responsibilities + functionality

Symfony/REST API admin: Patrick

```
webrtc/
 ├──app/                        
 |   ├──config/                                   * Patrick 
 |                                                  || project main config files
 ├──src/                        
 |   ├──AppBundle/                    
        ├──Api/                                   * Patrick 
        |                                           || Exception Handling + Response Handling
           ├──ApiProblem.php                        * + Code adapted from: http://knpuniversity.com/tracks/rest
           ├──ApiProblemException.php               * + Code adapted from: http://knpuniversity.com/tracks/rest
           ├──ResponseFactory.php                   * + Code adapted from: http://knpuniversity.com/tracks/rest
        ├──Controller/                            * Patrick + Markus (Based on Swagger api specification)
        |                                           || API Endpoints
           ├──...   
        ├──Entity/                                * Patrick, Sina, Tanja, Jennifer, Hadi, Markus
        |                                           || Entities from data model
           ├──...   
        ├──EventListener/                         * Patrick
        |                                           || Exception and Websocket Connect
           ├──ApiExceptionSubscriber.php            * + Code adapted from: http://knpuniversity.com/tracks/rest
           ├──...                                   
        ├──FormType/                              * Patrick
        |                                           || Formular handling
           ├──...   
        ├──Resources/                             * Patrick
        |                                           || Services and websocket channel routing
           ├──...   
        ├──Security/                              * Patrick
        |                                           || Token authentication handling
           ├──...   
        ├──Test/                                  * Patrick
        |                                           || Test configuration classes
           ├──ResponseAsserter.php                  * + Code adapted from: http://knpuniversity.com/tracks/rest
           ├──...                                   
        ├──Topic/                                 * Patrick
        |                                           || Websocket Signaling channel
           ├──...   
 │
 ├──tests/                                        * Patrick
 |                                                  || phpunit functional tests
    ├──...   
 │
 ├──phpunit.xml.dist                              * Patrick
 |                                                  || phpunit configuration
 ├──tests.bootstrap.php                           * Patrick, Sina, Tanja, Jennifer, Hadi 
 |                                                  || Test configurations and test data
 ├──swagger.yaml                                  * Markus 
 |                                                  || Swagger API specification
 │
```

All files/folders not mentioned here are part of the symfony bundle and/or vendor libraries.

Our files are also marked with references within the code.

## Credits

- Symfony: http://symfony.com/
- Ratchet: http://socketo.me/
- Apache: https://httpd.apache.org/
- Swagger: http://editor.swagger.io/

- Vendor packages (symfony, composer) used in the project, see composer.json file and: https://getcomposer.org/


## License

MIT License.
