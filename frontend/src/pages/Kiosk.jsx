
import React, { useState } from 'react';
import { api } from '../api';

const KiosK = () => {

    const [last, setLast] = useState(null);

    async function take(type) {
        const t = await api('/tickets', {
            method: 'POST', body:
                JSON.stringify({ type })
        });
        setLast(t.data);
    }

    return (
        <div className="p-6 grid gap-4">
            <h1 className="text-2xl font-bold">Ambil Nomor Antrian</h1>
            <div className="flex gap-4">
                <button className="px-4 py-2 bg-blue-600 text-white rounded"
                    onClick={() => take('R')}>Reservasi (R)</button>
                <button className="px-4 py-2 bg-green-600 text-white rounded"
                    onClick={() => take('W')}>Walk-in (W)</button>
            </div>
            {last && (
                <div className="text-3xl mt-3">Nomor Anda: <b>{last?.number}</b></div>
            )}
        </div>
    );

}

export default KiosK;