import React from "react";
import Logo from "./Logo";

const Header = () => {
    return (
    <header className="bg-pink-4 sticky top-0 z-[20] mx-auto flex w-full items-center flex-wrap justify-between p-8">
      <a href="/"><Logo></Logo></a>
    </header>
  );
};

export default Header;
