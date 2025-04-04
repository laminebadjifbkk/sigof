@extends('errors.layout', [
    'code' => 429,
    'title' => 'Trop de requêtes',
    'message' => "Vous avez envoyé trop de requêtes en peu de temps. Veuillez patienter un moment.",
])
