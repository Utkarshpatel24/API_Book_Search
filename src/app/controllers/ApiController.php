<?php

use Phalcon\Mvc\Controller;


class ApiController extends Controller
{
    /**
     * Function to Display Book List
     *
     * @return void
     */
    public function indexAction()
    {

        $this->view->books = array();
        $postdata = $this->request->getPost();
        if (count($postdata) > 0) {
            
            $bookname = explode(" ", $postdata['book']);
            $name = "";
            foreach ($bookname as $key => $val) {
                $name .=$val."+";
            }
            $name = substr($name, 0, strlen($name)-1);

            $url = "https://openlibrary.org/search.json?q=".$name."&mode=ebooks&has_fulltext=true";
            // Initialize a CURL session.
            $ch = curl_init();
            //to store in variable
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            
            //grab URL and pass it to the variable.
            curl_setopt($ch, CURLOPT_URL, $url);
            $response = curl_exec($ch);
            
            $response = json_decode($response);

            $detail = $response->docs;
            
            $this->view->books = $detail;
             
        }
        
        
    }
    /**
     * Function to display single book
     *
     * @param [type] $olid
     * @return void
     */
    public function bookDisplayAction($olid,$isbn = "")
    {
        
        $url = "https://openlibrary.org/api/books?bibkeys=olid:".$olid."&jscmd=details&format=json";
        // $url = "https://openlibrary.org/api/books?bibkeys=ISBN:".$isbn."&jscmd=details&format=json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        // echo "<pre>";
        // print_r(json_decode($response));
        // die;


        $response = ((array)json_decode($response))['olid:'.$olid];
        
        $book = ((array)$response->details);
        $this->view->book = $book;
        $this->view->olid = $olid;
        $linkId = ((array)$book['identifiers']);
        $this->view->googleId = "";
        if(array_key_exists('google', $linkId))
        $this->view->googleId = $linkId['google'];



    }
}