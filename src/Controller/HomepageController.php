<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Process\Process;

class HomepageController extends AbstractController
{
    /**
     * @Route("/homepage")
     */
    public function homepage()
    {

        $zoom = 17;

        $process = new Process(['python','C:\xampp\htdocs\SnapMap\public\MDTest.py','43.99128','22.87636','43.99241','22.87936',$zoom,'cecisymfony','1']);
        $process->run();

        if(!$process->isSuccessful()){
            throw new ProcessFailedException($process);
        }
        echo $process->getOutput();
        return new Response("hello");

    }


}