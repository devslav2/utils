import React from 'react';
import AuthenticatedLayout from '@/Layouts/Authenticated';
import { Inertia } from '@inertiajs/inertia';

const JobsIndex = ({ jobs }) => {
    return (
        <AuthenticatedLayout>
            <div className="container mx-auto p-4">
                <h1 className="text-2xl font-bold mb-6">Offerte di Lavoro</h1>
                
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    {jobs.map(job => (
                        <div
                            key={job.id}
                            className="border rounded-lg shadow p-4 flex flex-col justify-between"
                        >
                            <div>
                                <h2 className="text-xl font-semibold mb-2">{job.job_title}</h2>
                                <p className="text-gray-700 mb-2 line-clamp-3">{job.slug}</p>
                                <p className="text-sm text-gray-500 mb-1">Salario: {job.salary}</p>
                            </div>
                            <button
                                className="mt-4 bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600"
                                onClick={() => Inertia.get(`/job-offers/${job.id}`)}
                            >
                                Visualizza
                            </button>
                        </div>
                    ))}
                </div>
            </div>
        </AuthenticatedLayout>
    );
};

export default JobsIndex;