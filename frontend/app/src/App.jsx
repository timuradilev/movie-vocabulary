import './App.css'
import WordList from './WordList.jsx'

function App() {
    const words = [
        {id: 'word1', left: 'left txt dasdsadsadas', middle: 'word1', right: 'right txt'},
        {id: 'word2', left: 'left txt', middle: 'wordfdsfsdd2', right: 'right txtdsfsdfd'},
        {id: 'word3', left: 'left txt', middle: 'word3', right: 'right txt'},
        {id: 'word4', left: 'ldsffdsfsdfdseft txt', middle: 'word4', right: 'right txtdsfds'},
        {id: 'word5', left: 'left txt', middle: 'word5', right: 'right txt'}
    ];
    return (
        <WordList category="A1" items={words}/>
    )
}

export default App
