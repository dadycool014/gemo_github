<?php

namespace App\Controller;

use App\Service\CallGithub;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class GithubRepoController extends AbstractController
{


    #[Route('/', name: 'github_repo',methods: ['GET'])]
    public function index(CallGithub  $github): Response
    {
$response = $github->showReposUsingLanguages() ;

  return  new JsonResponse($response['data'],$response['code']) ;

    }
}
