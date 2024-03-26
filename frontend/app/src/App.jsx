import './App.css'
import WordList from './WordList.jsx'
import api from './api/words'
import React, {useState, useEffect} from 'react';
import SubtitlesPanel from "./SubtitlesPanel.jsx";

function App() {
    const [wordLists, setWordLists] = useState([]);
    const [currentSubtitlesFileId, setCurrentSubtitlesFileId] = useState(0);
    const [subtitles, setSubtitles] = useState([])

    const loadSubtitles = async () => {
        try {
            const response = await api.get('/subtitles/list');

            setSubtitles(response.data);
        } catch (err) {
            if (err.response) {
                console.log(err.response);
            }
            alert(`Error: ${err.message}`);
        }
    };

    const fetchWords = async (subtitles_file_id) => {
        try {
            const response = await api.get(`/subtitles/${subtitles_file_id}`);

            let lists = {};
            for (const word of response.data) {
                if (!(word.category in lists)) {
                    lists[word.category] = {category: word.category, words: []};
                }
                lists[word.category].words.push(word);
            }

            setWordLists(Object.values(lists));
        } catch (err) {
            if (err.response) {
                console.log(err.response);
            }
            alert(`Error: ${err.message}`);
        }
    }

    useEffect(() => {
        loadSubtitles();
        fetchWords(currentSubtitlesFileId);
    }, [currentSubtitlesFileId])

    const wordListsComponents = wordLists.map(list => <WordList key={list.category} category={list.category} items={list.words}/>)

    const uploadSubtitles = async (event) => {
        event.preventDefault();

        const file = event.target.files[0];
        
        const formData =  new FormData();
        formData.append('file', file);
        const config = {headers: {'content-type': 'multipart/form-data'}};

        try {
            const response = await api.post('/subtitles/upload', formData, config);
            setCurrentSubtitlesFileId(response.data.id);
        } catch (err) {
            if (err.response) {
               console.log(err.response);
            }
            alert(`Error: ${err.message}`);
        }
    }

    const chooseSubtitles = (e) => {
        setCurrentSubtitlesFileId(e.target.value)
    };

    return (
        <>
            <SubtitlesPanel subtitles={subtitles} uploadSubtitles={uploadSubtitles} chooseSubtitlesHandler={chooseSubtitles}/>
            <div>{wordListsComponents}</div>
        </>
    );
}

export default App
