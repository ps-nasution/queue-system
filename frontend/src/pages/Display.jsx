import { useEffect, useRef, useState } from 'react';
import { api } from '../api';

const Display = () => {

    const [items, setItems] = useState([]);
    const lastSpoken = useRef('');

    useEffect(() => {
        const t = setInterval(async () => {
            const response = await api('/display/current');
            const data = response.data || [];
            setItems(data);
            if (data?.[0]?.number && data[0].number !== lastSpoken.current) {
                console.log('Speaking', data[0].number);
                speak(`Nomor antrian ${spell(data[0].number)} dipersilakan ke loket.`);
                lastSpoken.current = data[0].number;
            }
        }, 1500);
        return () => clearInterval(t);
    }, []);

    function spell(num) {
        return num.split('').join(' ');
    }

    function speak(text) {
        if (!('speechSynthesis' in window)) return;
        window.speechSynthesis.cancel();
        const u = new SpeechSynthesisUtterance(text);
        u.lang = 'id-ID';
        window.speechSynthesis.speak(u);
    }

    return (
        <>
            <div className="p-10">
                <h1 className="text-4xl font-bold mb-6">Sedang Dipanggil</h1>
                {items.length === 0 ? (
                    <div className="text-2xl text-gray-500">Tidak ada panggilan saat ini</div>
                ) : (
                    <div className="grid gap-3">
                        {items.map((t) => (
                            <div key={t.id} className="text-3xl font-mono">
                                <span className="font-bold">{t.number}</span> <span
                                    className="text-gray-500">({t.type})</span>
                                {t.staff_name && <span className="ml-2">— {t.staff_name}</span>}
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </>
    )

}

export default Display;