```php
<?php

require_once __DIR__ . '/../src/Livre.php';
require_once __DIR__ . '/../src/Bibliotheque.php';

// Fonction de vérification des tests
function verifier(bool $condition, string $message): void
{
    if ($condition) {
        echo "[OK] $message\n";
    } else {
        echo "[ECHEC] $message\n";
        exit(1);
    }
}

// Création d'une bibliothèque
$bib = new Bibliotheque();

// Test : bibliothèque vide
verifier($bib->compter() === 0, 'Bibliotheque vide : compter() retourne 0');
verifier($bib->tous() === [], 'Bibliotheque vide : tous() retourne un tableau vide');

// Test : ajouter un livre
$l1 = new Livre('9782100545261', 'Algorithmique', 'Cormen');
$bib->ajouter($l1);
verifier($bib->compter() === 1, 'Apres ajout : compter() retourne 1');

// Test : ajouter un deuxième livre
$l2 = new Livre('9780132350884', 'Clean Code', 'Robert Martin');
$bib->ajouter($l2);
verifier($bib->compter() === 2, 'Apres 2 ajouts : compter() retourne 2');

// Test : ajouter un troisième livre
$l3 = new Livre('9780201633610', 'Design Patterns', 'Gamma');
$bib->ajouter($l3);
verifier($bib->compter() === 3, 'Apres 3 ajouts : compter() retourne 3');

// Test : ajout avec ISBN dupliqué
$exceptionDup = false;

try {
    $bib->ajouter(new Livre('9782100545261', 'Duplicate', 'Test'));
} catch (Exception $e) {
    $exceptionDup = true;
}

verifier($exceptionDup, 'Ajouter un ISBN deja present leve une exception');

// Test : trouver par ISBN existant
$trouve = $bib->trouver('9782100545261');
verifier(
    $trouve !== null && $trouve->getTitre() === 'Algorithmique',
    'trouver() retourne le bon livre'
);

// Test : trouver par ISBN inexistant
verifier(
    $bib->trouver('0000000000') === null,
    'trouver() retourne null si ISBN absent'
);

// Test : tous() retourne tous les livres
verifier(count($bib->tous()) === 3, 'tous() retourne les 3 livres');

// Test : rechercher par titre
$res = $bib->rechercher('algo');
verifier(
    count($res) === 1 && $res[0]->getTitre() === 'Algorithmique',
    'rechercher("algo") trouve Algorithmique'
);

// Test : rechercher par auteur
$res2 = $bib->rechercher('martin');
verifier(
    count($res2) === 1 && $res2[0]->getTitre() === 'Clean Code',
    'rechercher("martin") trouve Clean Code'
);

// Test : rechercher sans résultat
$res3 = $bib->rechercher('python');
verifier(count($res3) === 0, 'rechercher("python") ne trouve rien');

// Test : rechercher plusieurs résultats
$l4 = new Livre('9780596007126', 'Algo Avance', 'Cormen');
$bib->ajouter($l4);

$res4 = $bib->rechercher('algo');
verifier(count($res4) === 2, 'rechercher("algo") trouve 2 livres');

// Test : tauxDisponibilite()

// Bibliothèque vide
$bibVide = new Bibliotheque();
verifier(
    $bibVide->tauxDisponibilite() === 0.0,
    'tauxDisponibilite() retourne 0 pour une bibliotheque vide'
);

// Tous les livres disponibles
verifier(
    $bib->tauxDisponibilite() === 100.0,
    'tauxDisponibilite() retourne 100 pour des livres disponibles'
);

echo "\nTous les tests sont termines.\n";
