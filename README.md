# konjed
Konjed is a PHP framework which is developed according to a customized HMVC architectural pattern and it provides a set of tools and software facilities for client-side and server-side web development. 

Its Model layer contains a set of tools according to factory and also active record design patterns which makes the services over a mapping process with MySQL database server. However it’s going to support other database servers even NoSQL , graph databases and even LDAP.

Its Core layer is constructed over a general command design pattern which responds and manages whole requests and  processes through this command system. Its structure is based on multiple modules and every command represents a special function of a system module .

Its Controller layer is the main configuration of the way of responding to every request of user which makes the considered response using the core commands. It has a customized router class which routs every single rest request to a corresponding controller function of a controller class. It also has a set of modern useful tools called SculptorPHP which provides the ability of generating HTML, CSS and JavaScript tags, styles and scripts just by using the pure PHP components. However it’s going to be developed to make PHP Server Faces which   will be able to afford generate all UI elements by special xml markups by customized xml name space.

Its view layer is mainly using the Twig template engine from Symphony PHP framework with some additional tools provided by Konjed and it’s going to have the ability of supporting other template engines like Smarty or Blade.
