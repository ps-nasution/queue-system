import { useEffect, useState } from 'react';
import { api } from '../api'
import Card from '../components/Card';

const Dashboard = () => {

    const [data, setData] = useState(null);

    useEffect(() => {
        const t = setInterval(async () => {
            let response = await api('/dashboard')
            setData(response.data)
        }, 2000);
        return () => clearInterval(t);
    }, []);

    if (!data) return <div className="p-6">Loading...</div>;

    return (
        <>
            <div className="p-6 grid gap-4">
                <h1 className="text-2xl font-bold">Dashboard</h1>
                <div className="grid grid-cols-2 gap-4">
                    <Card title="Waiting">{data.waiting}</Card>
                    <Card title="Active Staff">{data.activeStaff}</Card>
                </div>
                <section>
                    <h2 className="text-xl font-semibold mt-4">Top 3 Staff</h2>
                    <ul className="list-disc ml-6">
                        {data.top?.map((s) => (
                            <li key={s.staff_id}>{s.name}: {s.total} tiket</li>
                        ))}
                    </ul>
                </section>
                <section>
                    <h2 className="text-xl font-semibold mt-4">Rata-rata Waktu Pelayanan</h2>
                    <ul className="list-disc ml-6">
                        {data.avg?.map((s) => (
                            <li key={s.staff_id}>{s.name}: {s.avg_seconds ?? 0}s</li>
                        ))}
                    </ul>
                </section>
            </div>
        </>
    )

}

export default Dashboard;