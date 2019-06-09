<?php

namespace App\Controller;

use App\Entity\ChatRoom;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\StateMachine;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, ContainerInterface $container)
    {
        $chatRoom = new ChatRoom();
        $chatRoom->setCurrentState($request->getSession()->get('currentState'));

        /** @var StateMachine $chatRoomStateMachine */
        $chatRoomStateMachine = $container->get('state_machine.chat_room');

        $transition = $request->request->get('transition');
        $error = null;
        if ($transition) {
            try {
                $chatRoomStateMachine->apply($chatRoom, $transition);
            } catch (LogicException $e) {
                $error = $e->getMessage();
            }
        }

        $request->getSession()->set('currentState', $chatRoom->getCurrentState());

        return $this->render(
            'default/index.html.twig',
            compact('transition', 'error', 'chatRoom') + [
                'transitions' => $chatRoomStateMachine->getEnabledTransitions($chatRoom),
            ]
        );
    }
}
