<?php


namespace App\Controller;


use App\Entity\File;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{

    /**
     * @Route("/upload")
     */
    public function index(Request $request): Response
    {

        return $this->render("upload.html.twig");
    }


    /**
     * @Route("/uploadedFile")
     */
    public function uploaded(Request $request): Response
    {
        $file = $request->files->get("coords");
        $uploads_dir = $this->getParameter('uploads_directory');

        $usrname = substr($_FILES['coords']['name'], 0, -4);
        $file_name = md5(uniqid()) . '.' . "gpx";

        $em = $this->getDoctrine()->getManager();


        if ($this->getDoctrine()->getRepository(User::class)->findOneBy(['Username' => $usrname]) != null) {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["Username" => $usrname]);


            $newfile = new File();
            $newfile->setCoordFile($uploads_dir . "/" . $file_name);
            $newfile->setCreatedAt(new \DateTime());
            $newfile->setUserId($user);

            $file->move($uploads_dir, $file_name);
            $em->persist($newfile);
            $em->flush();


        } else {
            $user = new User();
            $user->setCreatedAt(new \DateTime());
            $user->setUsername($usrname);
            $newfile = new File();
            $newfile->setCoordFile($uploads_dir . "/" . $file_name);
            $newfile->setCreatedAt(new \DateTime());
            $newfile->setUserId($user);
            $file->move($uploads_dir, $file_name);
            $em->persist($user);
            $em->persist($newfile);
            $em->flush();
        }


        return $this->render('uploadedFile.html.twig', [
            'coordsFileName' => $file_name,
            'usrname' => $usrname,
            'usr' => $user,
            'filee' => $newfile->getId()
        ]);
    }


    /**
     * @Route("/options/{file}", name="options")
     */
    public function checkOptions(Request $request,File $file)
    {
        $options=$request->get("gridRadios");
        $file=$this->getDoctrine()->getRepository(File::class)->findOneBy(['id'=>$file]);
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(['id'=>$file->getUserId()]);
        $onlyOpt = substr($options, 6);

        $fname = substr($file->getCoordFile(), -36);

        return $this->render('check.html.twig',[
            'options' => $options,
            'usrname' => $user->getUsername(),
            'usr' => $user,
            'filee' => $file,
            'fname' => $fname,
            'onlyOpt' => $onlyOpt
        ]);
    }

}