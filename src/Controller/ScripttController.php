<?php


namespace App\Controller;


use App\Entity\Coord;
use App\Entity\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Twig\Extension\CoreExtension;

class ScriptController extends AbstractController
{
    /**
     * @Route("/generate/{id}/{opt}", name="generate")
     * @param Request $request
     * @param $id
     * @param $opt
     * @return Response
     */
    public function generate(Request $request ,$id, $opt){
        $em = $this->getDoctrine()->getManager();
        $file=$this->getDoctrine()->getRepository(File::class)->find($id);

        $name = substr($file->getCoordFile(), -36);
//        dump($name,$file);
//        die;

        $gpx = simplexml_load_file("uploads/{$name}");
        $i=0;
        foreach ($gpx->wpt as $pt) {
            $lat = (string) $pt['lat'];
            $lon = (string) $pt['lon'];
            $ele = (string) $pt->ele;
            $i++;
            $double_lat = (double) $lat;
            $double_lon = (double) $lon;
            $formLat= number_format($double_lat,5);
            $formLon= number_format($double_lon,5);
            $upperLeftLat = $formLat + 0.00300;
            $upperLeftLon = $formLon - 0.00350;
            $lowerRightLat = $formLat - 0.00300;
            $lowerRightLon = $formLon + 0.00350;
            $zoom = 17;
            $process = new Process(['python','C:\xampp\htdocs\SnapMap\public/MD'. $opt .'.py ',$upperLeftLat,$upperLeftLon,$lowerRightLat,$lowerRightLon,$zoom,$i,substr($name,0,4)]);
            $process->run();

            if (!$process->isSuccessful()){
                throw new ProcessFailedException($process);
            }
            $output=$process->getOutput();


        }
        unset($gpx);

        $zipper = new ZipArchiver();
        $dirPath=substr($name,0,4);
        $zipPath=substr($name,0,4)."zip";

        $zip=$zipper->zipDir($dirPath,$zipPath);
        if($zip){
            $file->setArchiveFile($zipPath);
            $em->persist($em);
            $em->flush();
            $output .= "\n Archive created successfully.";
        }else{
            $output .= "\n Failed to archive the files";
        }

        return $this->render('processOutput.html.twig',[
            'output' => $output,
            'fileId'=> $id
        ]);


    }

    /**
     * @Route("/download/{id}", name="download")
     */
    public function download(Request $request,$id)
    {
        $file=$this->getDoctrine()->getRepository(File::class)->find($id);
        if (!empty($file)){
            return new BinaryFileResponse($file->getArchiveFile());
        }
    }

}