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
  