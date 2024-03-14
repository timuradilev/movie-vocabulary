import './App.css'
import WordList from './WordList.jsx'
import api from './api/words'
import React, {useState, useEffect} from 'react';

function App() {
    const [wordLists, setWordLists] = useState([]);
    const [currentSubtitles, setCurrentSubtitles] = useState('subtitles.srt');

    const fetchWords = async (subtitles) => {
        try {
            const response = await api.get(`/words/?filename=${subtitles}`);
            setWordLists(response.data);
        } catch (err) {
            if (err.response) {
                console.log(err.response);
            }
            alert(`Error: ${err.message}`);
        }
    }

    useEffect(() => {
        fetchWords(currentSubtitles)
    }, [currentSubtitles])

    const wordListsComponents = wordLists.map(list => <WordList key={list.category} category={list.category} items={list.words}/>)

    const uploadSubtitles = async (event) => {
        event.preventDefault();

        const file = event.target.files[0];
        
        const formData =  new FormData();
        formData.append('file', file);
        const config = {headers: {'content-type': 'multipart/form-data'}};

        try {
            await api.post('/upload', formData, config);
            setCurrentSubtitles(file.name);
        } catch (err) {
            if (err.response) {
               console.log(err.response);
            }
            alert(`Error: ${err.message}`);
        }
    }

    return (
        <>
            <form>
                <input type="file" onChange={uploadSubtitles} accept=".srt"/>
            </form>
            <div>{wordListsComponents}</div>
        </>
    );
}

export default App
