<?php


namespace App\Service;


use JetBrains\PhpStorm\Pure;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallGithub
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    private function getRepos()
    {
        $time = new \DateTime();
        $time->modify('-1 month');
        $gitdate = $time->format('Y-m-d');
        try {
            $response = $this->client->request("GET","https://api.github.com/search/repositories?q=created:>$gitdate&sort=stars&order=desc&per_page=100");
            $repos =$response->toArray();
             ;
        }catch (\Exception $e){
            $repos ['data'] = array('Failed To Fetch Repos') ;
            $repos ['code'] = 500 ;
        }



        return $repos;
    }

    private function getLanguagesAndRepos(): array
    {
        $data=$this->getRepos();
        if(isset($data["items"])){
            $repos = $data["items"] ;
        $languages=array_column($repos, 'language'); // Get languages of all repos
        $languages=array_values(array_unique(array_filter($languages))); // Remove nulls
        return ['languages'=>$languages,'repos'=>$repos];
        }else{
            return $data ;
        }
        }


    public function showLanguages()
    {
        return  $this->getLanguagesAndRepos()['languages'];
    }

    public function showReposUsingLanguages()
    {
        $languages_repos=$this->getLanguagesAndRepos();
if(isset($languages_repos['repos'])){
    $languages=$languages_repos['languages'];
    $repos=$languages_repos['repos'];

    $data=[];
    foreach ($languages as $key => $language) {

        // A callback function sent as a parameter to
        // array_filter funnction to return repos of a certain language
        $filter_callback=function ($value,$key) use ($language) {
            return $value['language']==$language;
        };
        $repos_using_language=array_values(array_filter($repos,$filter_callback,ARRAY_FILTER_USE_BOTH));

        $language_data=['language'=>$language,'number_of_repos'=>count($repos_using_language),'repos'=>$repos_using_language];
        array_push($data, $language_data);
    }
    $response ['data'] = $data ;
    $response ['code'] = 200 ;

}else{
    $response = $languages_repos ;
}


        return  $response ;
    }

}