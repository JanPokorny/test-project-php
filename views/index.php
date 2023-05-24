<h1>PHP Test Application</h1>

<h2>Users</h2>

<form class="form-horizontal">
    <div class="form-group">
        <label for="filter-city" class="col-sm-2 control-label">Filter by city:</label>
        <div class="col-sm-10">
            <select id="filter-city" name="filter-city" class="form-control">
                <option value="" selected> = All = </option>
                <?
                // Get unique cities from users
                $cities = array_unique(array_map(function ($user) {
                    return $user->getCity();
                }, $users));
                sort($cities);
                foreach ($cities as $city) {
                    ?> <option value="<?=$city?>"><?=$city?></option> <?
                }
                ?>
            </select>
        </div>
    </div>
</form>

<table class="table table-striped">
    <thead id="userHeader">
        <tr>
            <th>Name</th>
            <th>E-mail</th>
            <th>Phone number</th>
            <th>City</th>
        </tr>
    </thead>
    <tbody id="userRows">
        <?foreach ($users as $user){?>
        <tr>
            <td class="col-md-3"><?=$user->getName()?></td>
            <td class="col-md-3"><?=$user->getEmail()?></td>
            <td class="col-md-3"><?=$user->getPhoneNumber()?></td>
            <td class="col-md-3"><?=$user->getCity()?></td>
        </tr>
        <?}?>
    </tbody>
</table>

<hr>

<h2>Create a new user</h2>
<form method="post" action="create.php" class="form-horizontal" id="new-user-form">
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Name:</label>
        <div class="col-sm-10">
            <input name="name" input="text" id="name" class="form-control"/>
        </div>
    </div>

    <div class="form-group">
        <label for="email" class="col-sm-2 control-label">E-mail:</label>
        <div class="col-sm-10">
            <input name="email" input="text" id="email" class="form-control"/>
        </div>
    </div>

    <div class="form-group">
        <label for="phone_number" class="col-sm-2 control-label">Phone number:</label>
        <div class="col-sm-10">
            <input name="phone_number" input="text" id="phone_number" class="form-control"/>
        </div>
    </div>

    <div class="form-group">
        <label for="city" class="col-sm-2 control-label">City:</label>
        <div class="col-sm-10">
            <input name="city" input="text" id="city" class="form-control"/>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default">Create user</button>
        </div>
    </div>
</form>


<script>
$(() => {
    // FILTER ROWS BY CITY
    $('#filter-city').val('').change(function() {
        const city = $(this).val()
        const cityIndex = $("#userHeader th:contains('City')").index()

        // Show all rows
        $('#userRows tr').show()

        // Hide rows that doesn't match the filter
        if (city != '') {
            $('#userRows tr').each(function() {
                if ($(this).find('td').eq(cityIndex).text() != city) {
                    $(this).hide()
                }
            })
        }
    })

    // FORM SUBMIT
    $('#new-user-form').submit(function(e) {
        e.preventDefault()

        const inputs = $(this).find('input')

        // Check if all fields are filled
        if (inputs.toArray().some((input) => $(input).val() == '')) {
            alert('Please fill in all the fields')
            return false
        }

        // Disable form
        inputs.attr('disabled', false)
        $(this).find('button').text('Creating...').attr('disabled', true)

        // Send request to create new user
        $.post(
            'create.php',
            inputs
                .toArray()
                .map((input) => [input.name, $(input).val()])
                .reduce((obj, pair) => { obj[pair[0]] = pair[1]; return obj }, {})
        )
        .fail((data) => {
            alert(data.responseText)
        })
        .done((data) => {
            $('<tr>').append(
                inputs.toArray().map((input) => $('<td>').text($(input).val()))
            ).appendTo($('#userRows'))

            // If user's city is not in the filter, add it
            if ($('#filter-city option').filter((i, el) => $(el).val().length == 0)) {
                $('<option>').text($('#city').val()).appendTo($('#filter-city'))
                // Sort options alphabetically except the first one
                const originallySelected = $('#filter-city').val()
                $('#filter-city option').sort((a, b) => {
                    if ($(a).val() == '') return -1
                    if ($(b).val() == '') return 1
                    return $(a).val() > $(b).val() ? 1 : -1
                }).appendTo($('#filter-city'))
                $('#filter-city').val(originallySelected)
            }
            $('#filter-city').change()

            // Clear form
            inputs.val('')
            inputs.first().focus()
        })
        .always((data) => {
            inputs.attr('disabled', false)
            $(this).find('button').text('Create user').attr('disabled', false)
        })
    })
})
</script>