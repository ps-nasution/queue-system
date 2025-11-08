const Card = ({ title, children }) => {
    return (
        <div className="border rounded-lg p-4 shadow">
            <h3 className="text-lg font-medium mb-2">{title}</h3>
            <div className="text-2xl font-bold">{children}</div>
        </div>
    )
}

export default Card;