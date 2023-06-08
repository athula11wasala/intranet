<?php

namespace App\Controller;

use App\Entity\Country;
use App\Entity\Player;
use App\Entity\Teams;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;


class PlayerController extends AbstractController
{

   /**
     * @Route("/ajax/player", name="ajax_player")
     */
    public function viewAjaxPlayer(Request $request): Response
    {

        $teamId = $request->request->get('team_id')?? '';
        $player = $this->getDoctrine()->getRepository(Player::class)->getPlayerByTeam($teamId);
       
        $response = new Response(json_encode(array('data' => $player)));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    
    }

   
    /**
     * @Route("/team-info", name="view_team_info")
     */
    public function getTeamInfo(Request $request): Response
    {
        $teamId = $request->request->get('team_id')?? '';
        $team = $this->getDoctrine()->getRepository(Teams::class)->getTeam($teamId);
       
        $response = new Response(json_encode(array('data' => $team)));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/player/add", name="player_add")
     */
    public function addPlayer(Request $request,PaginatorInterface $paginator)
    {
 
     $playerRepository = $this->getDoctrine()->getRepository(Player::class);
     $teamInfo = $this->getDoctrine()->getRepository(Teams::class)->getTeamInfo();
     if(empty($teamInfo)){
    
        $this->addFlash('error', 'Team not found. please add teams');

        return $this->redirectToRoute('team_add');
     }

     $playerInfo = $playerRepository->getPlayerInfo();

      // Paginate the results of the query
      $playerInfo = $paginator->paginate(
        $playerInfo,
        $request->query->getInt('page', 1),
        5
    );

     
     if ($request->isMethod('post')) {

        $i = 0;
        foreach ($request->request->get('playerName') as $value) {

            $playerName =  ($request->request->get('playerName')[$i]);
            $surName =  ($request->request->get('surName')[$i]);
            $teamId =  ($request->request->get('team')[$i]);

            $playerRepository->addPlayer($playerName, $surName, $teamId);
            $i++;
        }
        $this->addFlash('message', 'Player added sucessfully');
        return $this->redirectToRoute('player_add');
        }

        return $this->render('player/add.html.twig', [
            'controller_name' => 'PlayerController',
            'teamInfo'=>$teamInfo,
            'playerInfo'=>$playerInfo
        ]);
    }

     /**
     * @Route("/player/edit/{id}", name="player_edit")
     */
    public function editPlayer($id, Request $request)
    {

        $playerInfo = $this->getDoctrine()->getRepository(Player::class)->find($id);

        if (empty($playerInfo)) {
            $this->addFlash('error', 'Player not found');

            return $this->redirectToRoute('player_add');
        }

        $atrributes = array('class' => 'form-control', 'style' => 'width:50%; margin-bottom:15px');
        $form = $this->createFormBuilder($playerInfo)
            ->add("name", TextType::class, array("attr" => $atrributes))
            ->add("surname", TextType::class, array("attr" => $atrributes))
            ->add("save", SubmitType::class, array("label" => 'Edit', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $playerRepository = $this->getDoctrine()->getRepository(Player::class);

            if ($playerRepository->editPlayer($playerInfo,  $form)) {

                $this->addFlash('message', 'Player updated sucessfully');
                return $this->redirectToRoute('player_add');
            }
        }
        return $this->render('player/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/player/delete/{id}", name="delete_player")
     */
    public function deleteAction($id)
    {
        $playerInfo = $this->getDoctrine()->getRepository(Player::class)->find($id);
        $playerRepository = $this->getDoctrine()->getRepository(Player::class);

        if ($playerRepository->deletePlayer($playerInfo)) {

            $this->addFlash('error', 'player removed sucessfully');
            return $this->redirectToRoute('player_add');
        }
    }


    /**
     * @Route("/player/sell", name="player_sell")
     */
    public function sellPlayer( Request $request)
    {

        $atrributes = array('class' => 'form-control', 'style' => 'width:50%; margin-bottom:15px');
        $form = $this->createFormBuilder()
             ->add('team', ChoiceType::class, array('choices' => array(),"attr" =>[ 'class' => 'form-control drp-team', 'style' => 'width:50%; margin-bottom:15px']))
             ->add('players', ChoiceType::class, array('choices' => array(),"attr" =>[ 'class' => 'form-control drp-players', 'style' => 'width:50%; margin-bottom:15px']))
             ->add('transaction', ChoiceType::class, array('choices' => array('Sell'=>0,'Buy'=>1),"attr" =>[ 'class' => 'drp-tan form-control ', 'style' => 'width:50%; margin-bottom:15px']))
             ->add('assignteam', ChoiceType::class, array('choices' => array(),"attr" =>[ 'class' => 'form-control drp-asignteam', 'style' => 'width:50%; margin-bottom:15px']))
             ->add("amount", TextType::class, array("attr" => $atrributes))
             ->add("save", SubmitType::class, array("label" => 'Submit', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            
            $playerRepository = $this->getDoctrine()->getRepository(Player::class);
            $playerInfo = $this->getDoctrine()->getRepository(Player::class)->find($request->get('form')['players'] );
            
            if ($playerRepository->playerAssingTeam($playerInfo,  $request->get('form'))) {

                $this->addFlash('message', 'Player assign sucessfully');
                return $this->redirectToRoute('player_add');
            }
        }
        return $this->render('player/sell.html.twig', array(
            'form' => $form->createView(),
        ));
    }
   
}
