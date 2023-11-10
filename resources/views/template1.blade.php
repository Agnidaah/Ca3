<!DOCTYPE html>
<html>
<head>
    <title>
        @if(request()->has('title'))
            {{ request('title') }}
        @else
            Default Title
        @endif
    </title>
</head>
<body>
<div class="tiles">
    @for($i = 1; $i < count($items); $i++)
        @php
            $item = $items[$i];
        @endphp
        <!-- Inside the for loop for tiles -->
        <div class="tile-container">
            <div class="tile" data-tile-name="{{ $item[0] }}" nextquery="{{$items[0][0]}}" @if(count($item) > 2) quantity="{{$item[1]}}"
         price="{{$item[2]}}" idd="{{$item[3]}}" @endif>
            <div class="upload-icon" id="{{$item[0]}}">&#8593;</div>
            <img src="{{ asset('images/' . $item[0] . '/a.jpeg') }}" alt="User Image">
                <span>{{ ucwords(str_replace('_', ' ', $item[0])) }}
                @if(count($item) > 2)
                        - Quantity: {{ $item[1] }}, Price: {{ $item[2] }}
                @endif</span>
            </div>
            @if($i!=count($items)-1)
                <button class="remove-button" data-tile-name="{{ $item[0] }}" nextquery="{{$items[0][0]}}">Remove</button>
            @endif
</div>
    @endfor
</div>

<div id="overlay"></div>
    <div id="dialog">
    <p id="error-message" style="color: red; display: none;">Please enter a positive number.</p>
        <label for="newcat" id="l1" style="display: none;">New Category:</label>
        <input type="text" name="name" id="newcat" style="display: none;">
        <!-- Input field for decimal value with up to 2 decimal places -->
        <label for="decimalValue" id="l2"style="display: none;">Price:</label>
        <input type="number" name="decimalValue" id="price" step="0.01" placeholder="0.00"style="display: none;">
        <label for="quantity" id="l3"style="display: none;">Quantity:</label>
        <input type="number" name="quantity" id="quantity" step="1" placeholder="0"style="display: none;">
        <label for="textValue" id="l4"style="display: none;">Text (up to 255 characters):</label>
        <input type="text" name="textValue" id="type" maxlength="255" placeholder="Type"style="display: none;" value="@isset($type){{ $type }}@endisset">
        <label for="idd" id="l5"style="display: none;">Product No:</label>
        <input type="number" name="idd" id="id" step="1" placeholder="0"style="display: none;">
        <h2>Confirmation</h2>
        <p>Do you want to continue?</p>
        <button id="confirmYes">Yes</button>
        <button id="confirmNo">No</button>
    </div>
    <div id="upload-form" style="display:none;">
    <form action="{{ route('uploadimage') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" id="image-upload-input">
        <input type="hidden" name="desired_filename" id="desired-filename-input" value="S">
        <button type="submit" id="upload-image-button">Upload Image</button>
        <button type="button" id="cancel-upload-button">Cancel</button>
    </form>
</div>
    <script>
const uploadIcons = document.getElementsByClassName('upload-icon');

for (let i = 0; i < uploadIcons.length; i++) {
  uploadIcons[i].addEventListener('click', function (event) {
    event.stopPropagation();
    document.getElementById('desired-filename-input').value = this.id;
    overlay.style.display = 'block';
    document.getElementById('upload-form').style.display = 'block';
  });
}
// Add an event listener to the "Cancel" button
document.getElementById('cancel-upload-button').addEventListener('click', function () {
    // Hide the "upload-form"
    overlay.style.display = 'none';
    document.getElementById('upload-form').style.display = 'none';
});

document.getElementById('upload-image-button').addEventListener('click', function () {
    document.getElementById('upload-form').style.display = 'none';
});

        document.getElementById("quantity").addEventListener("input", function () {
    // Get the current value
    let value = parseFloat(this.value);

    // Check if the value is negative
    if (value < 0) {
      // If it's negative, display an error message and reset the input value to 0
      document.getElementById("error-message").style.display = "block";
      this.value = 0;
    } else {
      // If it's not negative, hide the error message
      document.getElementById("error-message").style.display = "none";
    }
  });
        let queryy="";
        // Add a click event handler to the tiles
        const tiles = document.querySelectorAll('.tile');
        tiles.forEach(tile => {
            tile.addEventListener('click', function(event) {
                const tileName = this.getAttribute('data-tile-name');
                if(tileName=='Add Category' || this.getAttribute('nextquery')==3){queryy=this.getAttribute('nextquery');//last change
                    if(this.getAttribute('nextquery')==3 && tileName!='Add Category'){
                        document.getElementById('newcat').value=tileName;
                        document.getElementById('quantity').value=parseInt(this.getAttribute('quantity'));
                        document.getElementById('price').value=parseFloat(this.getAttribute('price'));
                        document.getElementById('id').value=parseInt(this.getAttribute('idd'));
                        document.getElementById("id").readOnly = true;
                    }
                callhelp();
            }else{
                window.location.href = `/category/?tile=${tileName}&nextquery=${this.getAttribute('nextquery')}`;}
            });
        });
function callhelp(){
    overlay.style.display = 'block';
    dialog.style.display = 'block';
    document.getElementById('newcat').style.display = 'block';
    document.getElementById('l1').style.display = 'block';
    if(queryy==2 || queryy==3){
        document.getElementById('price').style.display = 'block';
        document.getElementById('l2').style.display = 'block';
        document.getElementById('quantity').style.display = 'block';
        document.getElementById('l3').style.display = 'block';
        document.getElementById('id').style.display = 'block';
        document.getElementById('l5').style.display = 'block';
        document.getElementById('type').style.display = 'block';
        document.getElementById('l4').style.display = 'block';
    }
    if(queryy==3){
        document.getElementById("type").readOnly = true;
    }
}
        const overlay = document.getElementById('overlay');
const dialog = document.getElementById('dialog');
const confirmYesButton = document.getElementById('confirmYes');
const confirmNoButton = document.getElementById('confirmNo');
        // Select all "remove" buttons
const removeButtons = document.querySelectorAll('.remove-button');

// Add an event listener to each "remove" button
removeButtons.forEach(removeButton => {
    removeButton.addEventListener('click', () => {
        // Show the overlay and dialog when the button is clicked
        overlay.style.display = 'block';
        dialog.style.display = 'block';
        if(removeButton.getAttribute('nextquery')==1){//i dont know why 'this' is not working.
        queryy="drop table "+removeButton.getAttribute('data-tile-name');//again same problem
    }else if(removeButton.getAttribute('nextquery')==2){
        queryy="WHERE type = '"+removeButton.getAttribute('data-tile-name')+"'";
    }else{
        queryy="WHERE name = '"+removeButton.getAttribute('data-tile-name')+"'";
    }
    });
});
confirmYesButton.addEventListener('click', () => {
    // Handle 'Yes' button click
    if(document.getElementById('id').readOnly==true){
        window.location.href=`/update/?qu=
SET name = '${document.getElementById('newcat').value}',
    price = ${document.getElementById('price').value},
    quantity = ${document.getElementById('price').value},
    type = '${document.getElementById('type').value}'
WHERE id = ${document.getElementById('id').value};
`
    }else{
    if(queryy==1){
        window.location.href = `/crud/?nextquery=${queryy}&queryy= CREATE TABLE ${document.getElementById('newcat').value.replace(/\s/g, '_').replace(/\b\w/g, c => c.toLowerCase())} (id INT PRIMARY KEY,name VARCHAR(255) NOT NULL,price DECIMAL(10, 2) NOT NULL,quantity INT NOT NULL,type VARCHAR(50) NOT NULL);`;
    }
    
    else if(queryy==2 || queryy==3){
        window.location.href=`/crud/?nextquery=${queryy}&queryy=(id,name, price, quantity,type)
VALUES (${document.getElementById('id').value},"${document.getElementById('newcat').value}", ${document.getElementById('price').value},${document.getElementById('quantity').value},"${document.getElementById('type').value}");
`;}
    else{
    window.location.href = `/crud/?nextquery=-1&queryy=`+queryy;}
    closeDialog();}
});

confirmNoButton.addEventListener('click', () => {
    // Handle 'No' button click
    closeDialog();
});
function closeDialog() {
    overlay.style.display = 'none';
    dialog.style.display = 'none';
}
    </script>
</body>
</html>

