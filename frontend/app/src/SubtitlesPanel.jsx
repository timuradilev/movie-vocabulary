import './App.css'
import React from "react";

function SubtitlesPanel({subtitles, uploadSubtitles, chooseSubtitlesHandler}) {
    const list = subtitles.map(item => <option value={item.id} key={item.id} className="subtitles-list-item">{item.name}</option>);

    return (
        <div className="subtitles-panel">
            <div></div>
            <select onChange={chooseSubtitlesHandler}>
                {list}
            </select>
            <form className="subtitles-panel-upload">
                <label>
                    Upload .srt file
                    <input type="file" onChange={uploadSubtitles} accept=".srt"/>
                </label>
            </form>
        </div>
    )
}

export default SubtitlesPanel