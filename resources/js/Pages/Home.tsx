import Footer from '../Components/Footer';
import Header from '../Components/Header';
import Main from '../Components/Main';
import {usePage} from "@inertiajs/react";

function Home(user: any) {

    const { user_permissions } = usePage().props;

    // log session data
    console.log('Session Data: ', JSON.stringify(user_permissions, null, 2));
    return (
        <div>
            <Header></Header>
            <Main></Main>
            <Footer></Footer>
        </div>
    );
}

export default Home;


