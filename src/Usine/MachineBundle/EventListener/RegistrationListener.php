<?php
/**
 * Created by PhpStorm.
 * User: NABIL
 * Date: 24/08/2017
 * Time: 03:53
 */

namespace Usine\MachineBundle\EventListener;

use FOS\UserBundle\FOSUserEvents ;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Event\FilterUserResponseEvent;

class RegistrationListener implements EventSubscriberInterface
{

    public function onKernelResponse(FilterUserResponseEvent $event)
    {
        $event->stopPropagation();
    }

    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return array(
          FOSUserEvents::REGISTRATION_SUCCESS =>'onRegistrationSuccess'
        );
    }

    public function onRegistrationSuccess(FormEvent $event) {
        $roleadmin = array('ROLE_ADMIN');
        $roleuser =array('ROLE_USER');
        $user = $event->getForm()->getData();
        if ($user->getFonction()=='Admin'){
            $user->setRoles($roleadmin);
        }
    }
}