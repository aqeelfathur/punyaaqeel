<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Workout List - FIT TRACK</title>
    <link rel="stylesheet" href="{{ asset('css/styleslist.css') }}">
  </head>
  <body>
    <div class="container-list">
      <h1>Workout Movements</h1>
      <form id="workoutForm">
       
        <div class="card-container">
          <!-- Card  1: Push Up -->
          <div class="card">
            <div class="card-image" style="background-image: url('https://c.animaapp.com/m9iegn4yP1IE22/img/image-3-3.png');">
              
            </div>
            <div class="card-info">
              <h2>Push Up (25x)</h2>
              <input type="checkbox" class="latihan-checkbox" id="chk-pushup" />
            </div>
          </div>
          <!-- Card  2: Crunch -->
          <div class="card">
            <div class="card-image" style="background-image: url('https://c.animaapp.com/m9iegn4yP1IE22/img/image-3-3.png');">
            </div>
            <div class="card-info">
              <h2>Crunch (10x)</h2>
              <input type="checkbox" class="latihan-checkbox" id="chk-crunch" />
            </div>
          </div>
          <!-- Card  3: Back Up -->
          <div class="card">
            <div class="card-image" style="background-image: url('https://c.animaapp.com/m9iegn4yP1IE22/img/image-3-3.png');">
            </div>
            <div class="card-info">
              <h2>Back Up (10x)</h2>
              <input type="checkbox" class="latihan-checkbox" id="chk-backup" />
            </div>
          </div>
          <!-- Card  4: Sit Up -->
          <div class="card">
            <div class="card-image" style="background-image: url('https://c.animaapp.com/m9iegn4yP1IE22/img/image-3-3.png');">
            </div>
            <div class="card-info">
              <h2>Sit Up (10x)</h2>
              <input type="checkbox" class="latihan-checkbox" id="chk-situp" />
            </div>
          </div>
        </div>
        <!-- button tombol Finish Workout -->
        <button type="button" id="finishWorkout" disabled>Finish Workout</button>
      </form>
    </div>
</script> 

// list.js
document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.latihan-checkbox');
    const finishBtn = document.getElementById('finishWorkout');
  
    // Fungsi untuk mengecek apakah semua checkbox telah dicek
    function checkAllCompleted() {
      const allChecked = [...checkboxes].every(cb => cb.checked);
      finishBtn.disabled = !allChecked;
    }
  
    // Tambahkan event listener untuk setiap checkbox
    checkboxes.forEach(cb => {
      cb.addEventListener('change', checkAllCompleted);
    });
  
    // Event listener untuk tombol Finish Workout
    finishBtn.addEventListener('click', () => {
      if ([...checkboxes].every(cb => cb.checked)) {
        alert('Workout Selesai!');
        // Kembali ke halaman load
        window.location.href = "load.html";
      }
    });
  });
  
</script>
  </body>
</html>
