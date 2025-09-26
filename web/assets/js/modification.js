(function ($) {

    function gatherIngredients() {
        let ingredientsList = [];
        $('#ingredients-list li').each(function () {
            const $bloc = $(this);
            const id = $bloc.data('id');
            const quantity = $bloc.find('input[name$="[quantity]"]').val().trim();
            const unit = $bloc.find('input[name$="[unit]"]').val().trim();
    
            if (id && quantity) {
                ingredientsList.push({
                    id: id,
                    quantity: quantity,
                    unit: unit
                });
            }
        });
        return ingredientsList;
    }
    
    

    function gatherSteps() {
        let steps = [];
        $('#steps-container .step').each(function (index) {
            let description = $(this).find('textarea').val().trim();
            if (description) {
                steps.push({
                    description: description
                });
            }
        });
        return steps;
    }
    

    function sendRecipeModification() {
        const formData = new FormData();

        formData.append('idRecette', $('#idRecette').val() || '');
        formData.append('recipe-name', $('#recipe-name').val() || '');
        formData.append('description', $('#recipe-description').val() || '');
        formData.append('difficulty', $('#difficulty').val() || '');
        formData.append('prep-time', $('#prep-time').val() || '');
        formData.append('userId', $('#menu-deroulant').val() || '');

        formData.append('steps', JSON.stringify(gatherSteps()));
        formData.append('ingredients', JSON.stringify(gatherIngredients()));



        $.ajax({
            url: 'http://localhost/SwipeTonSouper_MySQL/web/api_tempo/recipe/sauvegarder_modification_recette.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                alert('Recette enregistrée avec succès !');
                localStorage.removeItem('manualRecipe');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Erreur:', textStatus, errorThrown);
                console.log('Réponse de l\'API:', jqXHR.responseText);
                alert("Erreur lors de l'enregistrement :\n" + jqXHR.responseText);

            }
        });
    }




    $(document).on('click', '#button-send', function () {
        sendRecipeModification();
    });

})(jQuery);
