import React, { useEffect, useState } from "react";
import { getUser, getIncomes } from "../api";

export default function Dashboard({ token, onLogout }) {
    const [user, setUser] = useState(null);
    const [incomes, setIncomes] = useState([]);

    useEffect(() => {
        getUser(token).then(setUser).catch(onLogout);
        getIncomes(token).then(setIncomes).catch(() => {});
    }, [token, onLogout]);

    return (
        <div style={{ maxWidth: 600, margin: "2em auto" }}>
            <h2>Dashboard</h2>
            <p>Hello, {user ? user.name : "loading"}!</p>
            <h3>Your incomes:</h3>
            <ul>
                {incomes.map((inc, idx) => (
                    <li key={idx}>
                        {inc.description} – {inc.amount_cents ? inc.amount_cents / 100 : inc.amount} ({inc.date})
                    </li>
                ))}
            </ul>
            <button onClick={onLogout}>Logout</button>
        </div>
    );
}