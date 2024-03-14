import './App.css'

function SubtitlesList({subtitles, chooseSubtitlesHandler}) {
    const list = subtitles.map(item => <li itemID={item} key={item} onClick={chooseSubtitlesHandler} className="subtitles-list-item">{item}</li>);

    return (
        <ul className="subtitles-list">
            {list}
        </ul>
    )
}

export default SubtitlesList