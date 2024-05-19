import axios from "axios";
import { useEffect, useState } from "react";

const App = () => {
  const [loadingDates, setLoadingDates] = useState(true);
  const [loadingPlayers, setLoadingPlayers] = useState(true);

  const [dates, setDates] = useState([]);
  const [currentDate, setCurrentDate] = useState("global");
  const [count, setCount] = useState(0);
  const [currentPage, setCurrentPage] = useState(1);
  const [players, setPlayers] = useState([]);

  const getDates = async () => {
    try {
      setLoadingDates(true);
      const response = await axios.get("https://mateqh.site/vanity-api/");
      setDates(response.data);
    } catch (err) {
      setDates([]);
    } finally {
      setLoadingDates(false);
    }
  };

  useEffect(() => {
    getDates();
  }, []);

  const getPlayers = async () => {
    try {
      setLoadingPlayers(true);
      const response = await axios.get(
        "https://mateqh.site/vanity-api/?date=" +
          currentDate +
          "&page=" +
          currentPage
      );
      setPlayers(response.data);
    } catch (err) {
      setPlayers([]);
    } finally {
      setLoadingPlayers(false);
    }
  };

  useEffect(() => {
    getPlayers();
  }, [currentPage, currentDate]);

  const getCount = async () => {
    try {
      //setLoadingCount(true);
      const response = await axios.get(
        "https://mateqh.site/vanity-api/?date=" + currentDate
      );
      setCount(response.data.count);
    } catch (err) {
      setCount(0);
    } finally {
      //setLoadingCount(false);
    }
  };

  useEffect(() => {
    getCount();
  }, [currentDate]);

  const totalPages = Math.ceil(count / 10);

  const prevPage = () => {
    setCurrentPage((prevPage) => prevPage - 1);
  };

  const nextPage = () => {
    setCurrentPage((prevPage) => prevPage + 1);
  };

  return (
    <div className="w-full h-screen py-4">
      <div className="w-full overflow-x-auto px-2">
        <div className="flex justify-start sm:justify-center space-x-4 px-4 py-2">
          {!loadingDates &&
            dates.map((date, index) => (
              <button
                key={index}
                className={`bg-gray-700 rounded-lg p-2 text-gray-400 uppercase text-sm whitespace-nowrap ${
                  date === currentDate ? "bg-gray-900" : ""
                }`}
                onClick={() => {
                  setCurrentDate(date);
                  setCurrentPage(1);
                }}
              >
                {date}
              </button>
            ))}
        </div>
      </div>

      <h1 className="text-center text-2xl font-bold uppercase my-6">
        Toplista
      </h1>

      <div className="w-full px-2 sm:px-8 md:px-36 lg:px-64 xl:px-96 rounded-lg">
        <table className="w-full overflow-x-auto rounded-lg">
          <thead className="text-xs text-gray-400 bg-gray-700 uppercase rounded-lg">
            <tr className="">
              <th scope="col" className="py-3 rounded-tl-lg border-r">
                Helyezés
              </th>
              <th scope="col" className="py-3 border-r">
                Játékosnév
              </th>
              <th scope="col" className="py-3 rounded-tr-lg">
                Ölések
              </th>
            </tr>
          </thead>
          <tbody className="border">
            {loadingPlayers ? (
              <tr>
                <td className="text-center py-4" colSpan={3}>
                  Adatok betöltése
                </td>
              </tr>
            ) : players.length === 0 ? (
              <tr>
                <td className="text-center py-4" colSpan={3}>
                  Nincs találat
                </td>
              </tr>
            ) : (
              Array.apply(0, Array(10)).map((_, index) =>
                players[index] ? (
                  <tr className="border-b text-center" key={index}>
                    <td className="py-4 border-r">
                      #{index + 1 + (currentPage - 1) * 10}
                    </td>
                    <td className="py-4 border-r ">
                      {players[index].name.slice(0, 16)}
                    </td>
                    <td className="py-4">{players[index].kills}</td>
                  </tr>
                ) : (
                  <tr className="border-b text-center" key={index}>
                    <td className="py-4 border-r">-</td>
                    <td className="py-4 border-r">-</td>
                    <td className="py-4">-</td>
                  </tr>
                )
              )
            )}
          </tbody>
        </table>
        <div className="flex justify-between mt-4 text-gray-400 text-sm">
          <button
            className="w-24 p-2 rounded-md bg-gray-700 uppercase"
            onClick={prevPage}
            disabled={currentPage === 1}
          >
            Előző
          </button>
          <div className="w-full flex justify-center">
            <input
              className="w-12 text-lg text-center focus:border"
              type="number"
              min={1}
              max={totalPages}
              value={currentPage}
              onChange={(e) => {
                const value = e.target.value.trim();
                if (value === "" || isNaN(value) || parseInt(value) < 1) {
                  setCurrentPage(1);
                } else {
                  setCurrentPage(parseInt(value));
                }
              }}
            />
            <p className="text-xl pt-1">/</p>
            <input
              className="w-16 text-lg text-center focus:border"
              type="number"
              disabled
              value={totalPages}
            />
          </div>
          <button
            className="w-24 p-2 rounded-md bg-gray-700 uppercase"
            onClick={nextPage}
            disabled={currentPage === totalPages}
          >
            Következő
          </button>
        </div>
      </div>
    </div>
  );
};

export default App;
