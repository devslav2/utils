export default function CandidateSection({ user }) {
    return (
        <div className="p-4 bg-blue-100 rounded-lg">
            <h3 className="font-semibold">Benvenuto candidato!</h3>
            <p>Email: {user.email}</p>
        </div>
    );
}
