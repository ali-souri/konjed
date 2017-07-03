<?php

namespace Core\Page\Template;

require_once __DIR__.'/../../../../vendor/autoload.php';
require_once __DIR__."/../Loader.php";
\Loader::load("all");
require_once __DIR__.'/../../service/config.php';

/**
 * --- This class is for handling of templates
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Page\Template
 */
Class TemplateHandler
{

    /**
     * --- This function makes the html from template and appropriate data
     * @param string $template The string value of template
     * @param array $template_data The data which is supposed to be used for template rendering
     * @param string $additional_info The additional information which is used if there is a special query in it and will be delivered to the template
     * @return string The rendered html
     */
    public function getTemplateHtml($template, $template_data , $additional_info)
    {
        $twig_file = $this->getTwigFile(__DIR__ . "/../../../view/template", $template->get_twig_name());
        $template_data['additional_info']=$additional_info;
        $system_info = json_decode(get_system_config(),true);
        $template_data['system_info']=system_config;
        // var_dump($template_data);
        return $twig_file->render($template_data);
    }

    /**
     * --- This function makes the string value of template
     * @param string $dir_name The address of direction
     * @param string $twig_name The name of twig file
     * @return string The string value of template
     */
    private function getTwigFile($dir_name, $twig_name)
    {

        \Twig_Autoloader::register();

        $loader = new \Twig_Loader_Filesystem($dir_name);
        // $twig = new \Twig_Environment($loader, array(
        //     'cache' => __DIR__ .'/../../../view/template/cache',
        // ));
        //----------------------------------------
        $twig = new \Twig_Environment($loader,array('debug' => true));
        $twig->addExtension(new \Twig_Extension_Debug());
        // $twig->addExtension(new obj());
        // $twig->addGlobal('obj', new obj());
        // $twig = new \Twig_Environment($loader);
        $twig->addExtension(new \Twig_Extensions_Extension_Text());
        $twig->addGlobal('tools', new \Core\Tools\TwigGlobalTools());

        $template = $twig->loadTemplate($twig_name);

        return $template;
    }
}

?>