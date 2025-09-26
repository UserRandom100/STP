

function montrerRecettes(){

    //code html qui sera inserer
    let AInserer = '';

    fetch(/*lien a fetch*/ )
        .then (reponse => reponse.json())
        .then (r => r.forEach((element) => {
            AInserer = AInserer +'<div class = "info">'+element['id']+''
        }))
        .catch(e => console.error(e));

    document.getElementById('tag-id').innerHTML = '<ol><li>html data</li></ol>';
}

function ajouterEtape(){

    const prefix = "conteneur-step"
    const monDiv = document.getElementById('conteneur-steps');
    console.log("monDiv = "+monDiv);
    const dernierElement = monDiv.lastElementChild;
    let lastId = prefix + '0';
    if(dernierElement){
        if(dernierElement.id){
            lastId = dernierElement.id;
        }
    }
    if (lastId.startsWith(prefix)) {
        lastNum = lastId.slice(prefix.length);
    }
    else{
        alert("Une erreur s'est produite ! "+ lastId);
    }
    console.log(lastNum);
    console.log(lastId);
    let chaine = lastNum;
    let lastNumInt = parseInt(chaine, 10);
    let num = lastNumInt+1;
    
    $("#conteneur-steps").append(
        '<div id="conteneur-step'+num+'" class ="conteneur-step">' +
            '<div class="titre-step-recipe">Etape '+num+'</div>' +
            '<input class="input_steps" type="text" id="step'+num+'" name="step'+num+'" placeholder="description de l\'étape" required>' +
        '</div>'
    );
}

function ajouterIngredient(nameIngrediant, quantiter){
    $.ajax({
        url: 'http://localhost/SwipeTonSouper_MySQL/web/api_tempo/ingredient/rechercher_ingredients.php',
        method: 'GET',
        dataType: 'json',
        data:{query:nameIngrediant},
        success: function(response) {
                response.forEach(item => {
                    $('#conteneurIngrediants').append(
                        '<div id="'+item.id+'" class = "conteneurUnIngredient">'+
                            '<a class = "conteneurNomIngredient">'+item.name +'</a>'+
                            '<input type="number" class = "conteneurQuantityIngredient" value = "'+ quantiter +'"></input>'+
                            '<a class = "conteneurUniterIngredient">'+(item.unitOfMeasure || '[undefined]')+'</a> '+
                        '</div>'
                    );
             }); 
           
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Erreur:', textStatus, errorThrown);
        }
 });
}

function ajouterRecette(){
   
 

        let ingredientsList = [
            
          ];
        $('.conteneurUnIngredient').each(function(index) {
            const $bloc    = $(this);
            const nom      = $bloc.find('.conteneurNomIngredient').text().trim();
            const quantity = $bloc.find('.conteneurQuantityIngredient').val().trim();
            const unit     = $bloc.find('.conteneurUniterIngredient').text().trim();
            
            ingredientsList.push({
                ingredient: nom,
                unitOfMeasure: unit,
                unite: quantity
            });
            
        });

            //on compile toutes les /tape dans le steps
        let steps = [];
        $('.input_steps').each(function(index) {
            let description = $(this).val().trim();
            if (description) {
            steps.push({
                numero: index + 1,
                description: description
            });
            }
        });

        console.log("les etape son"+steps+"\n");

        //on met les info des champ de text dans le POST
        let formData = new FormData();
        formData.append('name', $('#recipe-name').val() || '');
        formData.append('description', $('#recipe-description').val() || '');
        formData.append('difficulty', $('#recipe-difficulty').val() || '');
        formData.append('prepTime', $('#recipe-prepTime').val() || '');
        formData.append('userId',$('#menu-deroulant').val() || '');
        // Ajoutez d'autres champs, comme ingredients et steps sous forme de JSON si nécessaire :
        formData.append('steps', JSON.stringify(steps));
        formData.append('ingredients', JSON.stringify(ingredientsList));
        
          // Si une image a été ajoutée, l'ajouter au FormData
          if ($('#recipe-image')[0].files[0]) {
            const imageFile = $('#recipe-image')[0].files[0];
            const allowedTypes = ['image/jpeg', 'image/png'];
            if (allowedTypes.includes(imageFile.type)) {
              formData.append('image', imageFile);
            } else {
              alert('Veuillez télécharger une image JPEG ou PNG.');
              return; // Stoppe l'envoi si l'image n'est pas valide
            }
          }
        
        //envoie de la recette
        $.ajax({
            url: 'http://localhost/SwipeTonSouper_MySQL/web/api_tempo/recipe/enregistrer_recette.php',
            method: 'POST',
            data: formData,
            processData: false,  // Important pour ne pas transformer les données
            contentType: false,  // Permet à FormData de définir le bon Content-Type
            success: function(response) {
                alert('Recette enregistrée avec succès !');
                localStorage.removeItem('manualRecipe');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Erreur:', textStatus, errorThrown);
                console.log('Réponse de l\'API:', jqXHR.responseText);
                alert('Erreur lors de l\'enregistrement.');
            }
        });
}

function modifierRecette(){
   
        let ingredientsList = [];
        $('.conteneurUnIngredient').each(function() {
            const $bloc    = $(this);
            const id       = $bloc.attr('id');
            const quantity = $bloc.find('.conteneurQuantityIngredient').val().trim();
            const unit     = $bloc.find('.conteneurUniterIngredient').text().trim();
          
            // N'ajouter que si un ID et une quantité sont présents
            if (id && quantity) {
                console.log('Bloc ID =', id);
                ingredientsList.push({
                id: id,
                quantity: quantity,
                unit: unit
              });
            }else{
                console.log('Bloc ID erreur =', id);
            }

          });

            //on compile toutes les /tape dans le steps
        let steps = [];
        $('.input_steps').each(function(index) {
            let description = $(this).val().trim();
            if (description) {
            steps.push({
                numero: index + 1,
                description: description
            });
            }
        });

        //on met les info des champ de text dans le POST
        let formData = new FormData();
        formData.append('idRecette', $('#idRecette').val() || '');
        formData.append('recipe-name', $('#recipe-name').val() || '');
        formData.append('description', $('#recipe-description').val() || '');
        formData.append('difficulty', $('#recipe-difficulty').val() || '');
        formData.append('prep-time', $('#recipe-prepTime').val() || '');
        formData.append('userId',$('#menu-deroulant').val() || '');
        // Ajoutez d'autres champs, comme ingredients et steps sous forme de JSON si nécessaire :
        formData.append('steps', JSON.stringify(steps));
        formData.append('ingredients', JSON.stringify(ingredientsList));
        
          // Si une image a été ajoutée, l'ajouter au FormData
          if ($('#recipe-image')[0].files[0]) {
            const imageFile = $('#recipe-image')[0].files[0];
            const allowedTypes = ['image/jpeg', 'image/png'];
            if (allowedTypes.includes(imageFile.type)) {
              formData.append('recipe-image', imageFile);
            } else {
              alert('Veuillez télécharger une image JPEG ou PNG.');
              return; // Stoppe l'envoi si l'image n'est pas valide
            }
          }
        
        //envoie de la recette
        $.ajax({
            url: 'http://localhost/SwipeTonSouper_MySQL/web/api_tempo/recipe/sauvegarder_modification_recette.php',
            method: 'POST',
            data: formData,
            processData: false,  // Important pour ne pas transformer les données
            contentType: false,  // Permet à FormData de définir le bon Content-Type
            success: function(response) {
                alert('Recette enregistrée avec succès !');
                localStorage.removeItem('manualRecipe')
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Erreur:', textStatus, errorThrown);
                console.log('Réponse de l\'API:', jqXHR.responseText);
                alert('Erreur lors de l\'enregistrement.');
            }
        });
}

function afficherMenuModifier(id){

    $.ajax({
        url: 'http://localhost/SwipeTonSouper_MySQL/web/api_tempo/recipe/recette_par_id.php',
        method: 'GET',
        dataType: 'json',
        data:{id_recette:id},
        success: function(response) {
                let responseRecette = response['recipes']
                let recette = responseRecette['recipe'];
                let ingredients = response['ingredients'];
                let etapes = response['steps'];
                let id_createur = recette["id_creator"];
        
        
    
    $("#recette"+id).empty();
    $("#recette"+id).removeAttr("onclick");
    
    $("#recette"+id).append(
        '<div id="feedback"></div>'+
       '<form id="form-modif-admin">'+
       '<input class="form-text" type="text" id="idRecette" value = "'+id+'" readonly>'+
    '<div class="titre-nom-recipe">Nom de la recette</div>'+
    '<input class="form-text" type="text" id="recipe-name" name="recipe-name" placeholder="Nom de la recette..." value = "'+recette['title']+'" required>'+ 

    '<div class="titre-description-recipe">Description de la recette</div>'+
    '<textarea class="form-text" id="recipe-description" name="recipe-description" placeholder="description de la recette..." value = "'+recette['description']+'" required>'+recette['description']+'</textarea>'+

    '<div class="titre-difficulty-recipe">Difficulter de la recette</div>'+
    '<input class="form-text" type="number" id="recipe-difficulty" name="recipe-difficulty" placeholder="difficulter de la recette..." value = "'+recette['difficulty']+'" required>'+

    '<div class="titre-prepTime-recipe">Temps de préparation de la recette</div>'+
    '<input class="form-text" type="number" id="recipe-prepTime" name="prepTime-difficulty" placeholder="temp de préparation de la recette..."  value = "'+recette['prepTime']+'"  required>'+ 

    '<br>'+

    '<div class="titre-prepTime-recipe">Personne Créatrice</div>'+

    '<select name="menu-deroulant" id="menu-deroulant">'+
    '</select>'+

    
   

    '<br>'+

    '<div class="form-group">'+
      '<label for="recipe-image">Photo de la recette</label><br>'+
      '<input class="form-control" type="file" id="recipe-image" name="recipe-image" accept="image/*">'+
    '</div>'+

    '<br>'+

    '<div id="conteneurIngrediants"></div>'+

    '<button type="button" id="btn-add-ingredient">Ajouter ingrédient</button>'+

    '<script>'+
      '$("#btn-add-ingredient").on("click", function() {'+
        'var idIng = $("#menu-deroulant-ingrediant").val();'+
        'var quantite = $("#zone_de_text_quantiter").val();'+
        'ajouterIngredient(idIng, quantite);'+
      '});'+
    '</script>'+

    '<button type="button" id="btn-suprimer-ingredient">suprimer ingrédient</button>'+

    '<select name="menu-deroulant-ingrediant" id="menu-deroulant-ingrediant"></select>'+

    '<input type="number" id="zone_de_text_quantiter"><br>'+

    '<br>'+

    '<div id="conteneur-steps">'+
      
    '</div>'+

    '<button type="button" id="button-add-step" onclick="ajouterEtape()">Ajouter</button><br>'+
    '<button type="button" id="btn-suprimer-step">'+
        'suprimer étape'+
    '</button>'+ 
    '<button type="button" id="button-send" onclick="modifierRecette()">Enregistrer</button>'+

    '</form>'
        );
        
        //on vas chercher la liste des user possible et on la met dans un menue deroulant
        $.ajax({
            url: 'http://localhost/SwipeTonSouper_MySQL/web/api_tempo/user/getAllUser.php',
            method: 'GET',
            dataType: 'json', // Optionnel : si vous attendez du JSON
            success: function(response) {

                
                let tabObjet = response['user'];
                tabObjet.forEach(function(object, index) {
                
                    if(id_createur == object['id']){
                        $("#menu-deroulant").append(
                            ' <option value="'+object['id']+'" selected>'+object['name']+'</option>'
                        );
                    }else{
                        $("#menu-deroulant").append(
                        ' <option value="'+object['id']+'">'+object['name']+'</option>'
                        );
                    }
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Erreur:', textStatus, errorThrown);
            }
        });

        //on met la liste des ingrediant dans un menu deroulant
        $.ajax({
            url: 'http://localhost/SwipeTonSouper_MySQL/web/api_tempo/ingredient/rechercher_ingredients.php',
            method: 'GET',
            dataType: 'json',
            data:{query:"%"},
            success: function(response) {
                response.forEach(item => {
                    
                    $('#menu-deroulant-ingrediant').append(
                        '<option value="' + item.name + '">' + item.name +' '+ item.unitOfMeasure + '</option>'
                    );
                        
                 }); 
               
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Erreur:', textStatus, errorThrown);
            }
        });


        // Recuperer le tableau des ingrediants
        const tabIngr = response.recipes.ingredients;
        const tabEtapes = response.recipes.steps;
        console.log(tabIngr);
        // S’assurer que c’est bien un tableau avant de faire forEach
        if (Array.isArray(tabIngr)) {
            tabIngr.forEach(item => {
            $('#conteneurIngrediants').append(
                '<div id="'+item.id+'" class = "conteneurUnIngredient">' +
                '<a class = "conteneurNomIngredient">'+item.name +'</a>'+
                '<input type="number" class = "conteneurQuantityIngredient" value = "'+ item.quantity +'"></input>'+
                '<a class = "conteneurUniterIngredient">'+item.unitOfMeasure+'</a> '+
                '</div>'
            );
            });
        } else {
            console.error("response.recipes.ingredients n'est pas un tableau", tabIngr);
        }
        
        //inscription des etapes
        if (Array.isArray(tabEtapes)) {
            tabEtapes.forEach(item => {//--------------------------------------------------------
            
                $("#conteneur-steps").append(
                    '<div id="conteneur-step'+item.stepNumber+'" class ="conteneur-step">' +
                        '<div class="titre-step-recipe">Etape '+item.stepNumber+'</div>' +
                        '<input class="input_steps" type="text" id="step'+item.stepNumber+'" name="step'+item.stepNumber+'"  value = "'+item.description+'" placeholder="description de l\'étape" required>' +
                    '</div>'
                );

            });
        } else {
            console.error("response.recipes.ingredients n'est pas un tableau", tabIngr);
        }

        //gestion bouton suprimer steps
        $('#btn-suprimer-step').on('click', function() {
            $('#conteneur-steps').children().last().remove();
        });

        //gestion bouton suprimer ingredients
        $('#btn-suprimer-ingredient').on('click', function() {
            $('#conteneurIngrediants').children().last().remove();
        });

    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.log('Erreur:', textStatus, errorThrown);
    }
});
}

// demander a houng:
// - pourquoi on enregistre pas les alergie dans enregistrer_recette.php

///il reste a 
// - ajouter liste ingrediant
// - enregistrer la recette
/*
    liste des recette avec un bouton mofifier et suptimer
*/ 


let offset = 0;
const limit = 10;

function afficherRecettes(recettes) {
    if (!Array.isArray(recettes)) {
        console.error("Les données reçues ne sont pas un tableau");
        return;
    }

    recettes.forEach(recette => {
        const bloc = `
            <div class="recipe-box" id="recette${recette.id}">
                <h3>${recette.title}</h3>
                <p><strong>Description :</strong> ${recette.description}</p>
                <p><strong>Difficulté :</strong> ${recette.difficulty}</p>
                <p><strong>Temps de préparation :</strong> ${recette.prepTime} min</p>
                <button class="modify-btn" data-id="${recette.id}">Modifier</button>
                <button class="delete-btn" data-id="${recette.id}">Supprimer</button>
            </div>
        `;
        $('#recipe-container').append(bloc);
    });
}

function chargerRecettes() {
    $.getJSON(`http://localhost/SwipeTonSouper_MySQL/web/api_tempo/recipe/toutes_recettes.php?limit=${limit}&offset=${offset}`)
        .done(function (data) {
            if (data.length === 0) {
                $('#load-more').hide(); // Plus rien à charger
            } else {
                afficherRecettes(data);
                offset += limit;
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error("Erreur AJAX :", textStatus, errorThrown);
        });
}

$(document).ready(function () {
    chargerRecettes(); // Chargement initial

    $('#load-more').on('click', chargerRecettes);

    $(document).on("click", ".delete-btn", function (e) {
        e.stopPropagation();
        const recipeId = $(this).data("id");

        if (confirm("Voulez-vous vraiment supprimer cette recette ?")) {
            $.ajax({
                url: "http://localhost/SwipeTonSouper_MySQL/web/api_tempo/recipe/suprimer_recette_par_id.php",
                type: "DELETE",
                contentType: "application/json",
                data: JSON.stringify({ id: recipeId }),
                success: function (response) {
                    alert(response.message);
                    $(`#recette${recipeId}`).remove();
                },
                error: function (xhr) {
                    alert("Erreur lors de la suppression de la recette.");
                    console.error(xhr.responseText);
                }
            });
        }
    });

    $(document).on("click", ".modify-btn", function (e) {
        e.stopPropagation();
        const recipeId = $(this).data("id");
        afficherMenuModifier(recipeId);
    });
});
