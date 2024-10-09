<template>
  <div id="app">
      <header class="header">
        <img src="/logo.png" alt="Logo" class="header-logo" />
        <h1 class="header-title">NETMHS UPDATES</h1> <!-- Header Title -->
      </header>
     <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      
    };
  },
  methods: {
    // Define your methods here
  },
  mounted() {
    // Code to run when the component is mounted
  }
};
</script>

<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0; /* Remove default padding */
    overflow: auto; 
}
.header-logo {
    height: auto; /* Maintain aspect ratio */
    width: auto; /* Set width to 30% of the header */
    max-height: 30px; /* Limit maximum height */
    margin-right: 10px; /* Space between logo and title */
}
.header {
    width: 100%; /* Make the header full width */
    display: flex; /* Use flexbox for layout */
    justify-content: center; /* Center items horizontally */
    align-items: center; /* Center items vertically */
    padding: 10px; /* Padding around the header */
    background-color: #800000; /* Maroon header background color */
    color: white; /* White text color */
    position: fixed; /* Fix the header at the top */
    top: 0; /* Position it at the top */
    z-index: 1000; /* Ensure it stays above other content */
    height: auto;
    
}
.header-title {
    margin: 0; /* Remove default margin */
    font-size: 1.5em; /* Adjust font size */
    text-align:center;
    font-family: 'Georgia', serif; 
}


#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  text-align: center;
  color: #2c3e50;
  height: 100%;
  width:100%;
  flex-direction: column; /* Stack children vertically */
    justify-content: center; /* Center content vertically */
    align-items: center; 
}
@media (max-width: 768px) {
    #app {
        flex-direction: column; /* Stack vertically on smaller screens */
    }
}

@media (min-width: 769px) {
    #app {
        flex-direction: row; /* Adjust layout for larger screens if needed */
    }
}
@media (max-width: 480px){
    .header-logo{
        display:none;
    }
}
</style>