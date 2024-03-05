import './App.css'
import WordList from './WordList.jsx'
import api from './api/words'
import React, {useState, useEffect} from 'react';

function App() {
    const [category, setCategory] = useState('');
    const [words, setWords] = useState([]);

    useEffect(() => {
        const fetchWords = async () => {
            try {
                const response = await api.get('/words.json');
                setCategory(response.data.category);
                setWords(response.data.words);
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
    return (
        <WordList category={category} items={words}/>
    )
}

export default App
