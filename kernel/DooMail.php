<?php

/**
* PHP, une classe PHP simple. Dans le but simplifié l'utilisation de PDO
* @author Dakia Franck <dakiafranckinfo@gmail.com>
* @package Doo;
* @version 0.1.0
*/
namespace Doo;

/**
* DooMail, classe permettant d'envoyer des mails
*/
class DooMail
{

    /**
     *
     */
    const FORMAT = "[
        'to' => destination@maildomain.com,
        'subject' => you subject,
        'data' => your message
    ]";

    /**
     * @var null
     */
    private static $destination = null;
    /**
     * @var null
     */
    private static $subject = null;
    /**
     * @var null
     */
    private static $message = null;
    /**
     * @var null
     */
    private static $additionnalHeader = null;
    /**
     * @var bool
     */
    private static $factoryIsDefine = false;

    /**
    * factory, fonction permettant de construire le message
    * [
    *   'to' => destination@maildomain.com,
    *   'subject' => you subject,
    *   'data' => your message
    * ].
    * @param array, les informations au formar montre ci-dessus.
    */
    public static function factory(array $information, $cb = null)
    {

        if(!is_array($information))
        {

            if($cb !== null)
            {

                return call_user_func($cb, self::FORMAT);

            }

            return self::FORMAT;

        }

        self::$destination = $information['to'];
        self::$subject = $information['subject'];
        self::$message = $information['data'];

        if($cb !== null)
        {

            call_user_func($cb, self::FORMAT);

        }

    }


    /**
    * addHeader, fonction permettant d'ajouter des headers suplementaire.
    * @param array, un tableau comportant les headers du mail
    */
    public static function addHeader(array $heads)
    {

        # Vérification de entete
        if(is_array($heads))
        {

            self::$additionnalHeader = '';

            $i = 0;

            foreach($heads as $key => $value)
            {

                # construction de la chaine d'entete.
                self::$additionnalHeader .= $key . ":" . $value . ($i > 0 ? ", ": "");
                $i++;

            }

        }

        self::$factoryIsDefine = true;

    }

    /**
    * send,  fonction a executer apres l'execution du factory
    * @param function, fonction de rappel
    */
    public static function send($cb = null)
    {

        if(self::$factoryIsDefine)
        {

            if($cb !== null)
            {

                call_user_func_array($cb, ["Vous avez oublier de construir le message", self::FORMAT]);
                return null;

            }
            else
            {

                trigger_error("Vous avez oublier de construir le message", E_WARNING);

            }

        }

        if(self::$additionnalHeader)
        {

            $status = mail(self::$destination, self::$subject, self::$message, self::$additionnalHeader);

        }
        else
        {

            $status = mail(self::$destination, self::$subject, self::$message);

        }

        if($cb !== null)
        {

            call_user_func($cb, $status);

        }

    }

}
