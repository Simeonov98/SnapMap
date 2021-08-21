<?php


namespace App\Controller;

use App\Entity\File;
use App\Entity\User;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use ZipArchive;

class ScripttController extends AbstractController
{
    /**
     * @Route("/generate/{id}/{opt}", name="generate")
     * @param Request $request
     * @param $id
     * @param $opt
     * @return Response
     */
    public function generatee(Request $request, $id, $opt)
    {
        $em = $this->getDoctrine()->getManager();
        $file = $this->getDoctrine()->getRepository(File::class)->findOneBy(['id' => $id]);
        $uploads_dir = $this->getParameter('uploads_directory');
        $dirOfFolder = substr($uploads_dir, 0, -7);
        $name = substr($file->getCoordFile(), -36);


        $gpx = simplexml_load_file("uploads/{$name}");
        $i = 0;
        foreach ($gpx->wpt as $pt) {
            $lat = (string)$pt['lat'];
            $lon = (string)$pt['lon'];
            $ele = (string)$pt->ele;
            $i++;
            $double_lat = (double)$lat;
            $double_lon = (double)$lon;
            $formLat = number_format($double_lat, 5);
            $formLon = number_format($double_lon, 5);
            $upperLeftLat = $formLat + 0.00300;
            $upperLeftLon = $formLon - 0.00350;
            $lowerRightLat = $formLat - 0.00300;
            $lowerRightLon = $formLon + 0.00350;
            $zoom = 17;
            $process = new Process(['python', 'C:\xampp\htdocs\SnapMap\public/MD' . $opt . '.py ', $upperLeftLat, $upperLeftLon, $lowerRightLat, $lowerRightLon, $zoom, $i, substr($name, 0, -4)]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $output = $process->getOutput();


        }
        unset($gpx);

        $dirPath = $dirOfFolder . substr($name, 0, -4);
        $zipPath = $dirOfFolder . substr($name, 0, -4) . ".zip";


        $zip = self::zipDir($dirPath, $zipPath);
        if ($zip) {

            $output2 = "\n Archive created successfully.";
        } else {
            $output2 = "\n Failed to archive the files";
        }

        $file->setArchiveFile($zipPath);
        $em->persist($file);
        $em->flush();

        //samo za test na stranicata iztrii gi posle
//        $output="Alabalanicaturskapanica mnogo text asdasdjaosdjaosdj aois
//        jd aoj aosjdoajsd oaijsdoajsodijaosjdoajsodkjoasjdoajo jaojao jao
//        jaojaosdjaojdsoaijdoajdiauhdaysgdeirjpsjuhw eodij asdhf wuehr eur
//        pakd uedhfgasiodjfoaiujsh uyhfhrgidjfhush uughoifbh oedrfghediufrhg eiurhg";
//        $output2 = "tuka ima tuka nema";
//        $zipPath="asdasdasdasdasdasdasdasda";


        return $this->render('processOutput.html.twig', [
            'output' => $output,
            'fileId' => $id,
            'output2' => $output2,
            'outputzip' => $zipPath
        ]);


    }

    /**
     * @Route("/download/{id}", name="download")
     */
    public function downloadd(Request $request, $id)
    {
        $file = $this->getDoctrine()->getRepository(File::class)->find($id);
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id'=>$file->getUserId()]);

        $response = new BinaryFileResponse($file->getArchiveFile());
        $created=$file->getCreatedAt()->format('Y-m-d H:i:s');
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $user->getUsername().$created.".zip"
        );

        $response->headers->set("Content-Disposition",$disposition);

//        if (!empty($file)) {
//            return new BinaryFileResponse($file->getArchiveFile());
//        }
        if (!empty($file)) {
            return $response;
        }
    }

    /**
     * Zip a folder (including itself).
     *
     * Usage:
     * Folder path that should be zipped.
     *
     * @param $sourcePath string
     * Relative path of directory to be zipped.
     *
     * @param $outZipPath string
     * Path of output zip file.
     *
     */
    public static function zipDir($sourcePath, $outZipPath)
    {
        $pathInfo = pathinfo($sourcePath);
        $parentPath = $pathInfo['dirname'];
        $dirName = $pathInfo['basename'];

        $z = new ZipArchive();
        $z->open($outZipPath, ZipArchive::CREATE);
        $z->addEmptyDir($dirName);
        if ($sourcePath == $dirName) {
            self::dirToZip($sourcePath, $z, 0);
        } else {
            self::dirToZip($sourcePath, $z, strlen("$parentPath/"));
        }
        $z->close();

        return true;
    }

    /**
     * Add files and sub-directories in a folder to zip file.
     *
     * @param $folder string
     * Folder path that should be zipped.
     *
     * @param $zipFile ZipArchive
     * Zip file where files end up.
     *
     * @param $exclusiveLength int
     * Number of text to be excluded from the file path.
     *
     */
    private static function dirToZip($folder, &$zipFile, $exclusiveLength)
    {
        $handle = opendir($folder);
        while (FALSE !== $f = readdir($handle)) {
            // Check for local/parent path or zipping file itself and skip
            if ($f != '.' && $f != '..' && $f != basename(__FILE__)) {
                $filePath = "$folder/$f";
                // Remove prefix from file path before add to zip
                $localPath = substr($filePath, $exclusiveLength);
                if (is_file($filePath)) {
                    $zipFile->addFile($filePath, $localPath);
                } elseif (is_dir($filePath)) {
                    // Add sub-directory
                    $zipFile->addEmptyDir($localPath);
                    self::dirToZip($filePath, $zipFile, $exclusiveLength);
                }
            }
        }
        closedir($handle);
    }

}