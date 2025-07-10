import React from "react";

const Main = () => {
  const containerStyle = {
    backgroundColor: "#FFDAEA",
    padding: "20px",
    margin: "20px auto",
    borderRadius: "10px",
    width: "80%",
    boxShadow: "0 4px 8px rgba(0,0,0,0.1)",
  };

  const buttonStyle = {
    backgroundColor: "#78415A", 
    color: "white",
    padding: "10px 20px",
    borderRadius: "8px",
    border: "none",
    cursor: "pointer",
    marginTop: "10px",
  };

  return (
    <main>
      {/* Bloc Bienvenue */}
      <div style={containerStyle}>
        <h2>Bienvenue sur RÉUT!</h2>
        <p>
          RÉUT est une plateforme collaborative qui te permet de réserver facilement des salles à l'UTC pour tes activités (réunions, musique, danse, art, etc.).
        </p>
        <p>
          Que tu sois membre d'une association ou étudiant non membre, RÉUT te donne accès à différents espaces adaptés à tes besoins !
        </p>
        <p>
          Chaque réservation aide la communauté à mieux organiser les activités de l'école, alors n'hésite pas à utiliser RÉUT, et à respecter les créneaux pour que tout le monde en profite !
        </p>
        <a href="#"><button style={buttonStyle}>Connexion</button></a>
      </div>

      {/* Bloc Mot des auteurs */}
      <div style={containerStyle}>
        <h3>Le mot des auteurs</h3>
        <p style={{ fontSize: "0.9rem" }}>
          RÉUT est une application proposée par le SIMDE.
          <br />
          Cette version a été développée par Jeannette Garea Courtois, à l'aide de tous les membres de l'équipe, dans le cadre d'une T2-A suivie par M. BONNET.
          <br />
          Elle est basée sur les besoins spécifiques de la plateforme RÉUT pour faciliter la réservation de salles par les étudiants de l'UTC.
          <br />
          En cas de bug, de question ou de proposition d'amélioration, n'hésite pas à contacter le SIMDE sur tes réseaux préférés !
        </p>
      </div>
    </main>
  );
};

export default Main;

