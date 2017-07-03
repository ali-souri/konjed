<?php

namespace Core\Command;

require_once __DIR__ . "/../Loader.php";
\Loader::load("cores");

/**
 * --- CommandRules class determines and validate command rules
 * @author Ali Souri ap.alisouri@gmail.com
 * @package Core\Command
 */
class CommandRules {

    /** @var array Of the data of commands of system */
    private $system_command_function_data = array();

    /**
     * --- empty constructor of class
     */
    function __construct() {
        
    }

    /**
     * --- This function validates a command
     * @param command $command A system command
     * @return bool The result of validation of a command
     */
    public function validate($command) {
        $command_name = $command->get_name();
        $command_module_name = $command->get_module_name();
        $command_function_name = $command->get_action_name();
        $command_additional_data = $command->get_additional_data();

        $rules = $this->get_command_modules_info();

        if (array_key_exists($command_module_name, $rules)) {
            $inputs = array();
            foreach ($rules[$command_module_name]['actions'] as $action) {
                foreach ($action['functions'] as $number => $func_values) {
                    if ($func_values['name'] == $command_function_name) {
                        foreach ($command_additional_data as $input_name => $input_value) {
                            $input_rule_array = $this->get_input_rule_array_by_name($func_values['inputs'], $input_name);
                            if ($input_rule_array) {
                                if ($this->array_input_check($input_rule_array, $input_value)) {
                                    $inputs[] = $input_value;
                                }
                            } else {
                                //error echo
                                return FALSE;
                            }
                        }

                        $this->system_command_function_data = array("class_address" => $action['API_address_from_root'], "class_full_name" => "\\" . $action['API_namespace'] . $action['API_class_name'], "func_name" => $func_values['API_function_name'], "func_input_array" => $this->get_sequenced_input_array($command_additional_data, $func_values['inputs']));
                        return TRUE;
                    }
                }
            }
        }

        return FALSE;
    }

    public function get_command_func_data() {
        return $this->system_command_function_data;
    }

    /**
     * --- This function put the names of additional information in the correct sequense according to the inputs part of command rule
     * @param array $additional_data Additional data of command
     * @param array $func_rule_inputs The inputs part of command rule
     * @return array Returns all the names of additional info
     */
    private function get_sequenced_input_array($additional_data, $func_rule_inputs) {
        $return_array = array();
        foreach ($func_rule_inputs as $number => $func_rule_values) {
            $return_array[] = $additional_data[$func_rule_values['name']];
        }
        return $return_array;
    }

    /**
     * --- This function checks if the input array is involved in rules
     * @param array $input_array_rule The array of rule of input
     * @param string $input_value The inputs value to be checked
     * @return bool Whether check is correct or not
     */
    private function array_input_check($input_array_rule, $input_value) {
        if ($input_array_rule && $input_value) {
            if ($input_array_rule["type"] == gettype($input_value)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * @param array $input_array The considered array
     * @param string $name The name which is about to be found out whether to be in array or not
     * @return bool Whether check is correct or not
     */
    private function get_input_rule_array_by_name($input_array, $name) {
        foreach ($input_array as $number => $input_values) {
            if ($input_values['name'] == $name) {
                return $input_values;
            }
        }
        return FALSE;
    }

    /**
     * @return array The information of system command modules and their rules
     */
    public function get_command_modules_info() {
        return array(
            "Page" => array(
                "actions" => array(
                    array(
                        "API_namespace" => "Core\\",
                        "API_class_name" => "API",
                        "API_address_from_root" => "/src/system/API.php",
                        "functions" => array(
                            0 => array("name" => "Generate", "API_function_name" => "generate_page", "inputs" => array(
                                    0 => array("name" => "url_name", "type" => "string"),
                                    1 => array("name" => "additional_info", "type" => "array")
                                )
                            )
                        )
                    )
                )
            ),
            "Response" => array(
                "actions" => array(
                    array(
                        "API_namespace" => "Core\\Response\\",
                        "API_class_name" => "ResponseAPI",
                        "API_address_from_root" => "/src/system/core/response/ResponseAPI.php",
                        "functions" => array(
                            0 => array("name" => "ShowHtml", "API_function_name" => "echo_html", "inputs" => array(
                                    0 => array("name" => "html", "type" => "String")
                                )
                            ),
                            1 => array("name" => "GetViewFile", "API_function_name" => "echo_view_file", "inputs" => array(
                                    0 => array("name" => "view_folder_path", "type" => "String"),
                                    1 => array("name" => "fils_name", "type" => "String")
                                )
                            ),
                            2 => array("name" => "Refresh", "API_function_name" => "refresh_page", "inputs" => array()),
                            3 => array("name" => "EchoJson", "API_function_name" => "json_echo_from_array", "inputs" => array(
                                    0 => array("name" => "input_array", "type" => "array")
                                )
                            ),
                            4 => array("name" => "EchoStatusJson", "API_function_name" => "status_json_echo", "inputs" => array(
                                    0 => array("name" => "status_boolean", "type" => "bool"),
                                    1 => array("name" => "message", "type" => "string")
                                )
                            ),
                            5 => array("name" => "SystemRedirect", "API_function_name" => "redirect_to_path", "inputs" => array(
                                    0 => array("name" => "route", "type" => "string"),
                                )
                            ),
                            6 => array("name" => "EchoString", "API_function_name" => "echo_string", "inputs" => array(
                                    0 => array("name" => "input_string", "type" => "string"),
                                )
                            ),
                        )
                    )
                )
            ),
            "Session" => array(
                "actions" => array(
                    array(
                        "API_namespace" => "Core\\Session\\",
                        "API_class_name" => "SessionAPI",
                        "API_address_from_root" => "/src/system/core/session/SessionAPI.php",
                        "functions" => array(
                            0 => array("name" => "SessionStart", "API_function_name" => "SessionStart", "inputs" => array()),
                            1 => array("name" => "SessionDestroy", "API_function_name" => "SessionDestroy", "inputs" => array()),
                            2 => array("name" => "SessionSet", "API_function_name" => "SessionSetVariable", "inputs" => array(
                                    0 => array("name" => "key", "type" => "String"),
                                    1 => array("name" => "value", "type" => "String")
                                )
                            ),
                            3 => array("name" => "SessionGet", "API_function_name" => "SessionGetVariable", "inputs" => array(
                                    0 => array("name" => "key", "type" => "String")
                                )
                            )
                        )
                    )
                )
            ),
            "WebService" => array(
                "actions" => array(
                    array(
                        "API_namespace" => "Core\\WebService\\",
                        "API_class_name" => "WebServiceAPI",
                        "API_address_from_root" => "/src/system/core/webservice/WebServiceAPI.php",
                        "functions" => array(
                            0 => array("name" => "cURL", "API_function_name" => "cURL", "inputs" => array(
                                        0 => array("name" => "type", "type" => "string"),
                                        1 => array("name" => "url", "type" => "string"),
                                        2 => array("name" => "params", "type" => "array")
                                    )
                                )
                        )
                    )
                )
            ),
            "Instagram" => array(
                "actions" => array(
                    array(
                        "API_namespace" => "Core\\WebService\\",
                        "API_class_name" => "Instagram",
                        "API_address_from_root" => "/src/system/core/webservice/Instagram.php",
                        "functions" => array(
                            0 => array("name" => "OAuthStep1", "API_function_name" => "OAuthStep1",
                                "inputs" => array(
                                    0 => array("name" => "client_id", "type" => "string"),
                                    1 => array("name" => "redirect_url", "type" => "string")
                                )
                            ),
                            1 => array("name" => "OAuthStep3", "API_function_name" => "OAuthStep3",
                                "inputs" => array(
                                    0 => array("name" => "client_id", "type" => "string"),
                                    1 => array("name" => "redirect_url", "type" => "string"),
                                    2 => array("name" => "client_pass", "type" => "string"),
                                    3 => array("name" => "code", "type" => "string"),
                                )
                            ),
                            2 => array("name" => "GetOwnRecentMedia", "API_function_name" => "GetOwnMedia",
                                "inputs" => array(
                                    0 => array("name" => "ACCESS_TOKEN", "type" => "string"),
                                    1 => array("name" => "COUNT", "type" => "string"),
                                    2 => array("name" => "MIN_ID", "type" => "string"),
                                    3 => array("name" => "MAX_ID", "type" => "string"),
                                )
                            ),
                            3 => array("name" => "GetHashTagCount", "API_function_name" => "GetHashTagCount",
                                "inputs" => array(
                                    0 => array("name" => "ACCESS_TOKEN", "type" => "string"),
                                    1 => array("name" => "tag_name", "type" => "string"),
                                )
                            ),
                            4 => array("name" => "GetHashTagMedia", "API_function_name" => "GetHashTagMedia",
                                "inputs" => array(
                                    0 => array("name" => "ACCESS_TOKEN", "type" => "string"),
                                    1 => array("name" => "COUNT", "type" => "string"),
                                    2 => array("name" => "MIN_TAG_ID", "type" => "string"),
                                    3 => array("name" => "MAX_TAG_ID", "type" => "string"),
                                    4 => array("name" => "tag_name", "type" => "string"),
                                )
                            ),
                        )
                    )
                )
            ),
            "User" => array(
                "actions" => array(
                    array(
                        "API_namespace" => "Core\\User\\",
                        "API_class_name" => "Authentication",
                        "API_address_from_root" => "/src/system/core/user/AuthenticationAPI.php",
                        "functions" => array(
                            0 => array("name" => "Authenticate", "API_function_name" => "user_authentication", "inputs" => array(
                                    0 => array("name" => "username", "type" => "string"),
                                    1 => array("name" => "password", "type" => "string")
                                )
                            )
                        )
                    )
                )
            ),
            "Crud" => array(
                "actions" => array(
                    array(
                        "API_namespace" => "Core\\",
                        "API_class_name" => "API",
                        "API_address_from_root" => "/src/system/API.php",
                        "functions" => array(
                            0 => array("name" => "Write", "API_function_name" => "action_write_init", "inputs" => array(
                                    0 => array("name" => "action_name", "type" => "string"),
                                    1 => array("name" => "object_name", "type" => "string"),
                                    2 => array("name" => "data", "type" => "array")
                                )
                            ),
                            1 => array("name" => "Read", "API_function_name" => "action_read_init", "inputs" => array(
                                    0 => array("name" => "object_name", "type" => "string"),
                                    1 => array("name" => "data", "type" => "array")
                                )
                            )
                        )
                    )
                )
            ),
            "Time" => array(//please complete it fro example for other time zones and gregorian calender
                "actions" => array(
                    array(
                        "API_namespace" => "Core\\Time\\",
                        "API_class_name" => "TimeAPI",
                        "API_address_from_root" => "/src/system/core/time/TimeAPI.php",
                        "functions" => array(
                            0 => array("name" => "GetJalaliDateBoldTime", "API_function_name" => "multiline_date_bold_time", "inputs" => array()),
                            1 => array("name" => "GetJalaliTimeCustomFormat", "API_function_name" => "getDateWithFormat", "inputs" => array(0 => array("name" => "format", "type" => "string"))),
                            2 => array("name" => "ConvertToJalali", "API_function_name" => "ConvertToJalali", "inputs" => array(0 => array("name" => "time", "type" => "string"))),
                        )
                    )
                )
            ),
            "Search" => array(
                "actions" => array(
                    array(
                        "API_namespace" => "Core\\Search\\",
                        "API_class_name" => "SearchAPI",
                        "API_address_from_root" => "/src/system/core/search/SearchAPI.php",
                        "functions" => array(
                            0 => array("name" => "PostSearch", "API_function_name" => "get_searched_posts_data", "inputs" => array(0 => array("name" => "query_string", "type" => "string")))
                        )
                    )
                )
            ),
            "Test" => array(
                "actions" => array(
                    array(
                        "API_namespace" => "Core\\Test\\",
                        "API_class_name" => "TestAPI",
                        "API_address_from_root" => "/src/system/core/unit-test/TestAPI.php",
                        "functions" => array(
                            0 => array("name" => "ControllerCompleteTest", "API_function_name" => "ControllerCompleteTest", "inputs" => array(0 => array("name" => "class_name", "type" => "string"))),
                            1 => array("name" => "ControllerFileCompleteTest", "API_function_name" => "ControllerFileCompleteTest", "inputs" => array(0 => array("name" => "class_file_name", "type" => "string"))),
                            2 => array("name" => "ControllerFileMethodTest", "API_function_name" => "ControllerFileMethodTest", "inputs" => array(0 => array("name" => "class_name", "type" => "string"),
                                    1 => array("name" => "class_file_name", "type" => "string"),
                                    2 => array("name" => "method_name", "type" => "string")))
                        )
                    )
                )
            ),
            "Sculptor" => array(
                "actions" => array(
                    array(
                        "API_namespace" => "Core\\Sculptor\\",
                        "API_class_name" => "SculptorAPI",
                        "API_address_from_root" => "/src/system/core/Sculptor/SculptorAPI.php",
                        "functions" => array(
                            0 => array("name" => "GetSculptor", "API_function_name" => "get_sculptor_app", "inputs" => array()),
                        )
                    )
                )
            ),
        );
    }

}

?>