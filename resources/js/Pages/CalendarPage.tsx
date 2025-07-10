import React, { useEffect, useState } from 'react';
import { Calendar, dateFnsLocalizer } from 'react-big-calendar';
import format from 'date-fns/format';
import parse from 'date-fns/parse';
import startOfWeek from 'date-fns/startOfWeek';
import getDay from 'date-fns/getDay';
import fr from 'date-fns/locale/fr';
import axios from 'axios';
import 'react-big-calendar/lib/css/react-big-calendar.css';
import Header from '../Components/Header';
import Footer from '../Components/Footer';

const locales = {
  fr: fr,
};

const localizer = dateFnsLocalizer({
  format,
  parse,
  startOfWeek: () => 1,
  getDay,
  locales,
});

const CalendarPage = () => {
  const [events, setEvents] = useState([]);

  /*useEffect(() => {
    axios.get('http://127.0.0.1:8000/api/reservations')
      .then(response => {
        const data = response.data;

        const formattedEvents = data.map((item: any) => ({
          title: `Réservation étudiant ${item.student_id}`,
          start: new Date(item.date + 'T' + item.start_time),
          end: new Date(item.date + 'T' + item.end_time),
        }));

        setEvents(formattedEvents);
      })
      .catch(error => {
        console.error('Erreur API:', error);
      });
  }, []);*/

  return (
    <>
    <Header></Header>
    <div style={{ height: '600px', margin: '20px' }}>
      <h2>Calendrier des réservations</h2>
      {/*<Calendar
        localizer={localizer}
        events={events}
        startAccessor="start"
        endAccessor="end"
        style={{ height: 500 }}
      />*/}
    </div>
    <Footer></Footer>
    </>
  );
};

export default CalendarPage;




// const events = [
//   {
//     title: "Salle d'Art",
//     start: new Date(2025, 5, 26, 10),
//     end: new Date(2025, 5, 26, 11),
//   },
// ];
