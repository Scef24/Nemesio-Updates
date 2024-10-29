<template>
  <div class="main-container">
    <!-- Hamburger Menu for All Views -->
    <button class="hamburger" @click="toggleSidebar">
      <span class="hamburger-bar"></span>
      <span class="hamburger-bar"></span>
      <span class="hamburger-bar"></span>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar" :class="{ 'sidebar-hidden': !sidebarOpen, 'sidebar-open': sidebarOpen }">
      <h2 class="sidebar-title">Menu</h2>
      <ul class="sidebar-menu">
        <li><a href="/login" @click="goToLogin">Login</a></li>
        <li><a href="/about" @click="goToAbout">About</a></li>
      </ul>
    </aside>

    <!-- About Section -->
    <div class="content-container">
      <div class="about-section">
        <!-- Logo -->
        <div class="logo">
          <img src="/logo.png" alt="Logo" class="responsive-logo">
        </div>

        <!-- Social Links -->
        <div class="social-links">
          <a href="https://web.facebook.com/NETMHSSsgOfficialpage?_rdc=1&_rdr" target="_blank"><i class="fab fa-facebook fa-2x"></i></a>
          <a href="mailto:netmhsshs303193@gmail.com" target="_blank"><i class="fas fa-envelope fa-2x"></i></a>
         
        </div>

        <!-- Info Section -->
        <div class="info-section">
          <h2>About This Page</h2>
          <p>Nemesio Epifania Taneo Memorial High School is a public high school located at Brgy. Tingo-Lapu-Lapu City
            <br>It is donated by the Taneo Family to the people of Tingo-Lapu-Lapu City.
          </p>
          
          <h2>About the System</h2>
          <p>The system is designed to keep you updated with the latest announcements and information about the school. It includes features like a viewing announcements, news updates, and more.</p>
        </div>
<!--Mission and Vision-->
<div class="mission-vision-section">
          <div class="mission">
            <h2>Mission</h2>
            <p>To protect and promote the right of every Filipino
              <br>to quality, equitable, culture-based <br>
              and complete basic education where:
              <br><br>
              Students learn in a child-friendly, gender sensitive <br>
              safe and motivating environment. <br><br>
              Teachers facilitate learning and constantly nurture every learner<br>
              <br><br>
              Administrators and staff, as stewards of the institution, ensure an enabling and supportive environment <br>
              for effective learning to happen. <br><br>
              Family, community and other stakeholders are engaged <br>
              and share responsibility for developing lifelong learners.
            </p>
          </div>
          
          <div class="vision">
            <h2>Vision</h2>
            <p>We dream of Filipinos <br>
              who passionately love their country <br>
              and whose competencies and values <br>
              enable them to realize their full potential <br>
              and contribute meaningfully to building the nation. <br><br>
              As a learner-centered institutio <br>
              Department of Education continuously improves itself <br>
              to better serve its stakeholders.
            </p>
          </div>
          <div class="core-values">
       <h2>Core Values</h2>
    <p>
      Maka-Diyos <br>
      Maka-tao <br>
      Makakalikasan <br>
      Makabansa
    </p>
  </div>
        </div>
        <!-- Toggle Button -->
        <button @click="toggleFacultyList" class="toggle-button">
          {{ facultyListVisible ? 'Hide Faculty List' : 'Show Faculty List' }}
        </button>

        <!-- Faculty Members -->
        <div v-if="facultyListVisible" class="faculty-members">
          <div v-for="member in facultyMembers" :key="member.id" class="faculty-member">
            <h3>{{ member.name }}</h3>
            <p>{{ member.position }}</p>
          </div>
        </div>

        <!-- Mission and Vision Section -->
       
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      facultyMembers: [
        { id: 1, name: 'Paquibot, Ike JR. A', position: 'T-lll'  },
        { id: 2, name: 'Bahag, Jelma S.', position: 'ADAS ll' },
        { id: 3, name: 'Anabieza, Cathyrene H.', position: 'T-l' },
        { id: 4, name: 'Baguio, Shielame G.', position: 'T-ll' },
        { id: 5, name: 'Cuay, Maricel I.', position: 'T-l' },
        { id: 6, name: 'Dano, Rosana I.', position: 'T-l' },
        { id: 7, name: 'Empanso, April Mae M.', position: 'T-ll' },
        { id: 8, name: 'Igot Jennelyn, C.', position: 'T-l' },
        { id: 9, name: 'Inoc, Mark Joe J.', position: 'T-l' },
        { id: 10, name: 'Mahilum, Mysthea M.', position: 'T-l' },
        { id: 11, name: 'Maloloy-on, JanPaul P.', position: 'T-1' },
        { id: 12, name: 'Mediana, Wienslen Eman N.', position: 'T-l' },
        { id: 13, name: 'Mediana Paulina P.', position: 'T-1' },
        { id: 14, name: 'Nunez, Gretchen D.', position: 'SST-l' },
        { id: 15, name: 'Paquibot Aljun A.', position: 'T-lll' },
        { id: 16, name: 'Pogoy Arlene E.', position: 'T-ll' },
        { id: 17, name: 'Rodriguez, Joveliezel B.', position: 'T-l' },
        { id: 18, name: 'Sabang, Jessica B.', position: 'T-l' },
        { id: 19, name: 'Sumalinog, Emily C.', position: 'T-l' },
        { id: 20, name: 'Sobroso, Raymund B.', position: 'T-l' },
        { id: 21, name: 'Ybanez, Daisyrel S.', position: 'T-l' },
        { id: 22, name: 'Suello, Janeline', position: 'T-l' },
        { id: 23, name: 'Paquibot, Rosemarie A.', position: 'AOll' },

        // Add more faculty members as needed
      ],
      sidebarOpen: false, // Sidebar state
      facultyListVisible: false // Faculty list visibility state
    };
  },
  methods: {
    toggleSidebar() {
      this.sidebarOpen = !this.sidebarOpen;
    },
    goToLogin() {
      console.log("Navigating to Login");
      this.sidebarOpen = false;
    },
    goToAbout() {
      console.log("Navigating to About");
      this.sidebarOpen = false;
    },
    toggleFacultyList() {
      this.facultyListVisible = !this.facultyListVisible;
    }
  }
}
</script>

<style scoped>
.main-container {
  display: flex;
  min-height: 100vh;
  background-color: #fff;
  margin-top: 10px;
}

.hamburger {
  display: flex;
  flex-direction: column;
  gap: 5px;
  position: fixed;
  top: 10px;
  left: 20px;
  background: none;
  border: none;
  cursor: pointer;
  z-index: 2000; /* Ensure the hamburger is above other elements */
}

.hamburger-bar {
  width: 30px;
  height: 3px;
  background-color: #020202;
}

.sidebar {
  width: 220px; /* Default width */
  background-color: #800000;
  padding: 20px;
  box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
  color: white;
  z-index: 1500; /* Ensure the sidebar is above other elements */
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  transform: translateX(-100%); /* Hide sidebar by default */
}

.sidebar-title {
  color: white;
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
}

.sidebar-menu {
  list-style: none;
  padding: 0;
}

.sidebar-menu li {
  margin-bottom: 15px;
}

.sidebar-menu a {
  color: white;
  font-size: 18px;
  text-decoration: none;
  display: block;
  transition: color 0.3s ease;
}

.sidebar-menu a:hover {
  color: #ffe6f2;
}

.content-container {
  flex: 1;
  padding: 20px;
  transition: margin-left 0.3s ease;
  display: flex;
  justify-content: center;
  align-items: center;
}

.about-section {
  text-align: center;
  padding: 20px;
  background-color: #fff;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.responsive-logo, .responsive-photo {
  max-width: 100%;
  height: auto;
}

.social-links a {
  margin: 0 10px;
  text-decoration: none;
}

.faculty-members {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px; /* Add space between cards */
}

.faculty-member {
  background-color: #f9f9f9; /* Light background for cards */
  border: 1px solid #ddd; /* Border for cards */
  border-radius: 8px; /* Rounded corners */
  padding: 15px;
  width: 200px; /* Fixed width for uniformity */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
  transition: transform 0.2s; /* Smooth hover effect */
}

.faculty-member:hover {
  transform: translateY(-5px); /* Lift effect on hover */
}

.faculty-member h3 {
  font-size: 18px;
  margin-bottom: 5px;
}

.faculty-member p {
  font-size: 16px;
  color: #555;
}
.toggle-button {
  background-color: #800000;
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  margin-top: 20px;
  margin-bottom: 20px;
}



.sidebar-hidden {
  transform: translateX(-100%);
}

.sidebar-open {
  transform: translateX(0);
}

@media (max-width: 768px) {
  .sidebar {
    width: 150px; /* Adjusted width for smaller screens */
  }
}
</style>
