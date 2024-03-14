import './App.css'
import WordList from './WordList.jsx'
import api from './api/words'
import React, {useState, useEffect} from 'react';
import SubtitlesList from "./SubtitlesList.jsx";

function App() {
    const [wordLists, setWordLists] = useState([]);
    const [currentSubtitles, setCurrentSubtitles] = useState('subtitles');
    const [subtitles, setSubtitles] = useState([])

    const loadSubtitles = async () => {
        try {
            const response = await api.get('/subtitles');

            setSubtitles(response.data);
        } catch (err) {
            if (err.response) {
                console.log(err.response);
            }
            alert(`Error: ${err.message}`);
        }
    };

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
        loadSubtitles();
        fetchWords(currentSubtitles);
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
            setCurrentSubtitles(file.name.replace('.srt', ''));
        } catch (err) {
            if (err.response) {
               console.log(err.response);
            }
            alert(`Error: ${err.message}`);
        }
    }

    const chooseSubtitles = (e) => {
        setCurrentSubtitles(e.target.getAttribute('itemId'))
    };

    return (
        <>
            <SubtitlesList subtitles={subtitles} chooseSubtitlesHandler={chooseSubtitles}/>
            <form>
                <input type="file" onChange={uploadSubtitles} accept=".srt"/>
            </form>
            <div>{wordListsComponents}</div>
        </>
    );
}

export default App
