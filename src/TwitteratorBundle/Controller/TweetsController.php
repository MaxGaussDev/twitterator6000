<?php
/**
 * \brief   
 * \details     
 * @author  Mario Pastuović
 * @version 1.0
 * \date 30.11.16.
 * \copyright
 *     This code and information is provided "as is" without warranty of
 *     any kind, either expressed or implied, including but not limited to
 *     the implied warranties of merchantability and/or fitness for a
 *     particular purpose.
 *     \par
 *     Copyright (c) Gauss d.o.o. All rights reserved
 * Created by PhpStorm.
 */

namespace TwitteratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Request;

use TwitteratorBundle\Entity\User;
use TwitteratorBundle\Entity\Post;

class TweetsController extends Controller
{

    public function userProfileAction($screen_name)
    {

        // checking for user
        $em = $this->getDoctrine()->getManager()->getRepository('TwitteratorBundle:User');
        $user = $em->findOneBy(array('screenName' => $screen_name));

        if (!$user){

            $conn = $this->get('twitterator.test');
            $chk = $conn->executeRoute('users/show',array('screen_name' => $screen_name));

            if(isset($chk->errors)){

                return $this->render('TwitteratorBundle:Main:error.html.twig', array("errors" => $chk->errors));
            }

            // add new user
            $user = new User($chk->id, $chk->screen_name, ucfirst($chk->name), new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // check for last 20 posts (user is added for the first time)
            $chk = $conn->executeRoute('statuses/user_timeline',array('user_id' => $user->getId(),'screen_name' => $screen_name, "count" => 20));

            // if error
            if(isset($chk->errors)){
                return $this->redirectToRoute('twitterator_profile', array('screen_name' => $screen_name));
            }

            // add tweets to database
            $em = $this->getDoctrine()->getManager()->getRepository('TwitteratorBundle:User');
            if($em->insertPosts($chk, $user->getId())){
                return $this->redirectToRoute('twitterator_profile', array('screen_name' => $screen_name));
            }

        }

        // checking for user posts (since it is the same route)
        $posts = $this->getDoctrine()
            ->getRepository('TwitteratorBundle:Post')
            ->findBy(array('userIdFk' => $user->getId()),array(),$this->getParameter('pagination-limit'));

        return $this->render('TwitteratorBundle:Main:index.html.twig', array("user" => $user, "posts" => $posts));

    }

    public function usersListAction()
    {
        $users = $this->getDoctrine()
            ->getRepository('TwitteratorBundle:User')
            ->findAll();

        if(!$users){

            $error = new \stdClass();
            $error->code = 404;
            $error->message = "No users were found. Try inserting some of them. It's easy as stealing candy from a baby.";

            return $this->render('TwitteratorBundle:Main:error.html.twig', array("errors" => array($error)));
        }

        return $this->render('TwitteratorBundle:Main:index.html.twig', array("users" => $users));
    }

    public function searchAction()
    {

        $request = Request::createFromGlobals();

        $serchTerm = $request->get('search_term');
        $user_id = $request->get('user_id');



        $users = $this->getDoctrine()
            ->getRepository('TwitteratorBundle:User')
            ->findAll();

        // normally it doesn't go like this, but this is just an example so...

        if(empty($serchTerm) && $user_id == 0){

            return $this->render('TwitteratorBundle:Main:search.html.twig', array("users" => $users));

        }else{

            $em = $this->getDoctrine()->getManager()->getRepository('TwitteratorBundle:User');

            if(!empty($serchTerm) && $user_id != 0){

                // both conditions are set, we search both of them
                $results = $em->searchPosts($serchTerm, $user_id);
                return $this->render('TwitteratorBundle:Main:search.html.twig', array("results" => $results, "users" => $users));

            }else{

                if(empty($serchTerm)){

                    $results = $em->searchPosts(null, $user_id);
                    return $this->render('TwitteratorBundle:Main:search.html.twig', array("results" => $results, "users" => $users));

                }elseif($user_id == 0){

                    $results = $em->searchPosts($serchTerm, null);
                    return $this->render('TwitteratorBundle:Main:search.html.twig', array("results" => $results, "users" => $users));

                }else{

                    return $this->render('TwitteratorBundle:Main:search.html.twig', array("users" => $users));

                }
            }

        }

    }

}