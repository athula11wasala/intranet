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


class TeamController extends AbstractController
{

    /**
     * @Route("/team", name="view_team")
     */
    public function viewTeam(Request $request): Response
    {
        $team = $this->getDoctrine()->getRepository(Teams::class)->findAll();

        return $this->render('team/view.html.twig', array(
            'teams' => $team
        ));
    }

    /**
     * @Route("/team/add", name="team_add")
     */
    public function addTeam(Request $request)
    {

        $teamInfo = $this->getDoctrine()->getRepository(Teams::class)->findAll();
        $country = $this->getDoctrine()->getRepository(Country::class)->getCountry();

        $atrributes = array('class' => 'form-control', 'style' => 'width:50%; margin-bottom:15px');
        $form = $this->createFormBuilder()
            ->add("name", TextType::class, array("attr" => $atrributes,))
            ->add('country', ChoiceType::class, array(
                'choices' => $country,
                'attr' => $atrributes
            ))
            ->add("save", SubmitType::class, array("label" => 'Save', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $teamRepository = $this->getDoctrine()->getRepository(Teams::class);

            if ($teamRepository->addTeam($form->getData())) {

                $this->addFlash('message', 'Team added sucessfully');

                return $this->redirectToRoute('team_add');
            }
        }

        return $this->render('team/add.html.twig', [
            'controller_name' => 'TeamController',
            'form' => $form->createView(),
            'teams' => $teamInfo
        ]);
    }

    /**
     * @Route("/team/edit/{id}", name="team_edit")
     */
    public function editTeam($id, Request $request)
    {

        $objTeam = $this->getDoctrine()->getRepository(Teams::class)->find($id);

        if (empty($objTeam)) {
            $this->addFlash('error', 'Team not found');

            return $this->redirectToRoute('view_team');
        }

        $country = $this->getDoctrine()->getRepository(Country::class)->getCountry();

        $atrributes = array('class' => 'form-control', 'style' => 'width:50%; margin-bottom:15px');
        $form = $this->createFormBuilder($objTeam)
            ->add("name", TextType::class, array("attr" => $atrributes))
            ->add('country', ChoiceType::class, array(
                'choices' => $country,
                'attr' => $atrributes
            ))
            ->add("save", SubmitType::class, array("label" => 'Edit', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $teamRepository = $this->getDoctrine()->getRepository(Teams::class);

            if ($teamRepository->editTeam($objTeam,  $form)) {

                $this->addFlash('message', 'Team updated sucessfully');
                return $this->redirectToRoute('team_add');
            }
        }
        return $this->render('team/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/team/delete/{id}", name="delete_team")
     */
    public function deleteTeam($id)
    {

        $objTeam = $this->getDoctrine()->getRepository(Teams::class)->find($id);
        $teamRepository = $this->getDoctrine()->getRepository(Teams::class);
        $checkExistPlayer = $this->getDoctrine()->getRepository(Player::class)->getPlayerByTeam($id);

        if(!empty($checkExistPlayer)){

            $this->addFlash('error', "Can't be deleted. This team alreday using for players.");
            return $this->redirectToRoute('team_add'); 
        }
       

        if ($teamRepository->deleteTeam($objTeam)) {

            $this->addFlash('error', 'Team removed sucessfully');
            return $this->redirectToRoute('team_add');
        }
    }
}
