import './App.css'

function SubtitlesList({subtitles, chooseSubtitlesHandler}) {
    const list = subtitles.map(item => <li itemID={item.id} key={item.id} onClick={chooseSubtitlesHandler} className="subtitles-list-item">{item.name}</li>);

    return (
        <ul className="subtitles-list">
            {list}
        </ul>
    )
}

export default SubtitlesList