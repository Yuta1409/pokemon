<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PokemonController extends AbstractController
{
    #[Route('/', name: 'app_pokemon_list')]
    public function list(PokemonRepository $pokemonRepository): Response
    {
        $pokemons = $pokemonRepository->findAll();
        return $this->render('pokemon/list.html.twig', [
            'pokemons' => $pokemons,
        ]);
    }

    #[Route('/create', name: 'app_pokemon_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if($request->isMethod('POST')) {
            $pokemon = new Pokemon();
            $pokemon->setName($request->request->get('name'));
            $pokemon->setType($request->request->get('type'));

            $entityManager->persist($pokemon);
            $entityManager->flush();

            return $this->redirectToRoute('app_pokemon_list');
        }

        return $this->render('pokemon/create.html.twig', [
            'controller_name' => 'PokemonController',
        ]);
    }

    #[Route('/edit/{id}', name: 'app_pokemon_edit')]
    public function edit(int $id, Request $request, PokemonRepository $pokemonRepository, EntityManagerInterface $entityManager): Response
    {
        $pokemon = $pokemonRepository->find($id);

        if ($request->isMethod('POST')) {
            $pokemon->setName($request->request->get('name'));
            $pokemon->setType($request->request->get('type'));

            $entityManager->flush();

            return $this->redirectToRoute('app_pokemon_list');
        }

        return $this->render('pokemon/edit.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_pokemon_delete')]
    public function delete(int $id, EntityManagerInterface $entityManager, PokemonRepository $pokemonRepository): Response
    {
        $pokemon = $pokemonRepository->find($id);
        $entityManager->remove($pokemon);
        $entityManager->flush();
        return $this->redirectToRoute('app_pokemon_list');
    }

    #[Route('/show/{id}', name: 'app_pokemon_show')]
    public function show(int $id, PokemonRepository $pokemonRepository): Response
    {
        $pokemon = $pokemonRepository->find($id);
        return $this->render('pokemon/show.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }
}
