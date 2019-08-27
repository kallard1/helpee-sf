<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
 * @author  Kevin Allard <contact@allard-kevin.fr>
 * @license 2018
 */

namespace App\Controller;

use App\Entity\Ad\Ad;
use App\Entity\Ad\Message\Message;
use App\Entity\Ad\Message\Thread;
use App\Entity\User;
use App\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MessagesController.
 *
 * @Route("/messages", name="messages")
 * @IsGranted("ROLE_USER")
 */
class MessagesController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="_inbox")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function inbox(Request $request): Response
    {
        return $this->render('messages/inbox.html.twig', [
            'messages' => $this->getDoctrine()->getManager()->getRepository(Thread::class)->getUserThreads($this->getUser()),
        ]);
    }

    /**
     * @Route("/new/{slug}", methods={"GET", "POST"}, name="_new")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Ad\Ad                         $ad
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, Ad $ad): Response
    {
        $thread = new Thread();
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $adUser = $em->getRepository(User::class)->findOneBy([
                'id' => $ad->getUser(),
            ]);

            $thread->setAd($ad);
            $thread->addParticipants([$this->getUser(), $adUser]);
            $thread->setCreatedBy($this->getUser());

            $message->setSender($this->getUser());
            $message->setThread($thread);

            $em->persist($thread);
            $em->persist($message);
            $em->flush();
        }

        return $this->render('messages/new.html.twig', [
            'ad' => $ad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/read/{id}", methods={"GET", "POST"}, name="_read")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Ad\Message\Thread             $thread
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function read(Request $request, Thread $thread): Response
    {
        $em = $this->getDoctrine()->getManager();

        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setThread($thread);
            $message->setSender($this->getUser());

            $em->persist($message);
            $em->flush();
        }

        return $this->render('messages/read.html.twig', [
            'thread' => $em->getRepository(Thread::class)->getThread($thread)[0],
            'form' => $form->createView(),
        ]);
    }
}
