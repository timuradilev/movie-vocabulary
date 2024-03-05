

function WordList ({category, items}) {
    const listItems = items.map(item => <li key={item.id}>
        <span className="word-list-left-text">{item.left}</span>
        <span className="word-list-middle-text">{item.middle}</span>
        <span className="word-list-right-text">{item.right}</span>
    </li>)
    return (
        <>
            <h3 className="word-list-category">{category}</h3>
            <ul className="word-list-items">{listItems}</ul>
        </>
    )
}

export default WordList