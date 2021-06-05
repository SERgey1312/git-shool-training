<?php


namespace App\Service;

use App\Entity\User;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

class Mailer
{
    public const FROM_ADDRESS = 'serg.vass123@gmail.com';

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private  $twig;

    public function __construct(Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;

    }

    /**
     * @param User $user
     */
    public function sendConfirmationMessageReg(User $user)
    {
        $messageBody = $this->twig->render('registration/confirmation.html.twig', [
            'user' => $user
        ]);

        $message = new Swift_Message();
        $message
            ->setSubject('Вы успешно прошли регистрацию!')
            ->setFrom(self::FROM_ADDRESS)
            ->setTo($user->getEmail())
            ->setBody($messageBody, 'text/html');

        $this->mailer->send($message);
    }

    /**
     * @param User $user
     */
    public function sendConfirmationMessageSub(User $user, $course)
    {
        $messageBody = $this->twig->render('course/email.html.twig', [
            'user' => $user,
            'course' => $course
        ]);

        $message = new Swift_Message();
        $message
            ->setSubject('Вы подписались на курс!')
            ->setFrom(self::FROM_ADDRESS)
            ->setTo($user->getEmail())
            ->setBody($messageBody, 'text/html');

        $this->mailer->send($message);
    }
}