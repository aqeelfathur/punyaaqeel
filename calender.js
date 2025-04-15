// JavaScript untuk fungsionalitas interaktif
document.addEventListener('DOMContentLoaded', function() {
    // Data latihan (bisa diganti dengan data dari server/database)
    const workoutData = {
      '2025-04-03': [
        { title: 'Upper Program', description: 'Chest, Shoulders & Triceps' },
        { title: 'Lower Program', description: 'Quads, Hamstrings & Calves' }
      ],
      '2025-04-07': [
        { title: 'Full Body Program', description: 'Compound movements' }
      ],
      '2025-04-10': [
        { title: 'Upper Program', description: 'Back & Biceps' }
      ],
      '2025-04-14': [
        { title: 'Lower Program', description: 'Legs & Core' }
      ],
      '2025-04-17': [
        { title: 'Push Program', description: 'Chest, Shoulders & Triceps' }
      ],
      '2025-04-21': [
        { title: 'Pull Program', description: 'Back & Biceps' }
      ],
      '2025-04-24': [
        { title: 'HIIT', description: 'High Intensity Interval Training' }
      ],
      '2025-04-28': [
        { title: 'Recovery', description: 'Mobility & Stretching' }
      ]
    };
    
    const calendarDays = document.querySelectorAll('.calendar-day');
    const workoutDate = document.querySelector('.workout-date');
    const workoutList = document.querySelector('.workout-list');
    
    // Menambahkan event listener untuk setiap tanggal di kalender
    calendarDays.forEach(day => {
      day.addEventListener('click', function() {
        // Hapus kelas active dari semua tanggal
        calendarDays.forEach(d => d.classList.remove('active'));
        
        // Tambahkan kelas active ke tanggal yang diklik
        this.classList.add('active');
        
        const date = this.getAttribute('data-date');
        if (date) {
          // Format tanggal untuk ditampilkan
          const displayDate = new Date(date).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          });
          
          // Update tanggal yang ditampilkan
          workoutDate.textContent = displayDate;
          
          // Clear dan update list workout
          workoutList.innerHTML = '';
          
          // Cek apakah ada workout pada tanggal tersebut
          if (workoutData[date]) {
            workoutData[date].forEach(workout => {
              const workoutItem = document.createElement('div');
              workoutItem.classList.add('workout-item');
              workoutItem.innerHTML = `
                <div class="workout-title">${workout.title}</div>
                <div class="workout-description">${workout.description}</div>
              `;
              workoutList.appendChild(workoutItem);
            });
          } else {
            // Jika tidak ada workout
            const noWorkout = document.createElement('div');
            noWorkout.classList.add('workout-item');
            noWorkout.innerHTML = `
              <div class="workout-title">Tidak ada latihan</div>
              <div class="workout-description">Belum ada program latihan yang dijadwalkan.</div>
            `;
            workoutList.appendChild(noWorkout);
          }
        }
      });
    });
    
    // Navigasi bulan (bisa diimplementasikan lebih lanjut)
    const prevMonth = document.querySelector('.prev-month');
    const nextMonth = document.querySelector('.next-month');
    
    prevMonth.addEventListener('click', () => {
      alert('Navigasi ke bulan sebelumnya. Fitur ini dapat diimplementasikan lebih lanjut.');
    });
    
    nextMonth.addEventListener('click', () => {
      alert('Navigasi ke bulan berikutnya. Fitur ini dapat diimplementasikan lebih lanjut.');
    });
  });