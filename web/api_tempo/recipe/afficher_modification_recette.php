<?php

// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Headers: Content-Type, Authorization");
// header("Content-Type: application/json; charset=UTF-8");

// Démarrer la session pour accéder à la variable $_SESSION
//session_start();

include_once(__DIR__ . "/../../modele/step.class.php");
include_once(__DIR__ . "/../../modele/DAO/StepDAO.class.php");

            $idRecette = intval($idRecette);
            $steps = StepDAO::findByIdRecipe($idRecette);
            if (!empty($steps)) {
                $stepIndex = 1;
                foreach ($steps as $step) {
                    echo '<div class="form-group step" id="step_' . $stepIndex . '">';
                    echo '<label>Étape ' . $stepIndex . '</label>';
                    echo '<button type="button" class="remove-step" data-step="step_' . $stepIndex . '">🗑️ Supprimer</button><br>';
                    echo '<textarea class="form-control step-input" name="step_' . $stepIndex . '" placeholder="Description de l\'étape..." required>';
                    echo htmlspecialchars($step->getStepDescription());
                    echo '</textarea>';
                    echo '</div>';
                    $stepIndex++;
                }
            }
?>