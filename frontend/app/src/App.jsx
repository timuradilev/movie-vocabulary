import './App.css'
import WordList from './WordList.jsx'
import api from './api/words'
import React, {useState, useEffect} from 'react';

function App() {
    const [wordLists, setWordLists] = useState([]);

    useEffect(() => {
        const fetchWords = async () => {
            try {
                const response = await api.get('/words');
                setWordLists(response.data);
            } catch (err) {
                if (err.response) {
                    console.log(err.response);
                } else {
                    console.log(`Error: ${err.message}`);
                }
            }
        }
        
        fetchWords();
    }, [])

    const wordListsComponents = wordLists.map(list => <WordList category={list.category} items={list.words}/>)

    return (
        <div>{wordListsComponents}</div>
    );
}

export default App
