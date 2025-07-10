import Footer from '../Components/Footer';
import Header from '../Components/Header';
import Main from '../Components/Main';

function Home(user: any) {

    console.log(user);
    return (
        <div>
            <Header></Header>
            <Main></Main>
            <Footer></Footer>
        </div>
    );
}

export default Home;


